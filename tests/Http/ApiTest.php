<?php

namespace Tests\Feature;

use App\Deposit;
use App\Withdrawal;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @test
     */
    public function a_user_can_view_balance()
    {
        $response = $this->json('GET', 'api/balance');
        $response
            ->assertStatus(200)
            ->assertJson([
                'id' => 1,
                'balance' => 0
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_make_a_deposit()
    {
        $response = $this->json('POST', '/api/deposit', ['bank_account_id' => 1, 'amount' => 3790, 'reason' => 'test it']);
        $response
            ->assertStatus(201)
            ->assertJson([
                'amount' => 3790,
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_not_deposit_more_than_transaction_limit()
    {
        $response = $this->json('POST', '/api/deposit', ['bank_account_id' => 1, 'amount' => 40001, 'reason' => 'test it']);
        $response
            ->assertStatus(403)
            ->assertExactJson([
                "code" => 403,
                "message" => "Exceeded maximum deposit amount per transaction"
            ]);

        $response = $this->json('POST', '/api/deposit', ['bank_account_id' => 1, 'amount' => 89000, 'reason' => 'test it']);
        $response
            ->assertStatus(403)
            ->assertExactJson([
                "code" => 403,
                "message" => "Exceeded maximum deposit amount per transaction"
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_not_exceed_deposit_transactions()
    {
        factory(Deposit::class)->times(3)->create(["amount" => 40000]); // Enter in database 3 deposits with total of 120k
        $response = $this->json('POST', '/api/deposit', ['bank_account_id' => 1, 'amount' => 20000, 'reason' => 'test it']);
        $response
            ->assertStatus(201)
            ->assertJson([
                "amount" => 20000
            ]);

        $response = $this->json('POST', '/api/deposit', ['bank_account_id' => 1, 'amount' => 20000, 'reason' => 'test it']);
        $response
            ->assertStatus(403)
            ->assertExactJson([
                "code" => 403,
                "message" => "Exceeded maximum deposit transactions allowed per day"
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_not_deposit_more_than_daily_limit()
    {
        factory(Deposit::class)->times(3)->create(["amount" => 40000]); // Enter in database 3 deposits with total of 120k
        $response = $this->json('POST', '/api/deposit', ['bank_account_id' => 1, 'amount' => 30001, 'reason' => 'test it']);
        $response
            ->assertStatus(403)
            ->assertExactJson([
                "code" => 403,
                "message" => "Exceeded maximum deposit amount per day"
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_make_a_withdrawal()
    {
        factory(Deposit::class)->create(['amount' => 3790]); //Ensure account has balance
        $response = $this->json('POST', '/api/withdraw', ['bank_account_id' => 1, 'amount' => 3790, 'reason' => 'clear balance']);
        $response
            ->assertStatus(201)
            ->assertJson([
                'amount' => 3790,
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_not_withdraw_more_than_transaction_limit()
    {

        $response = $this->json('POST', '/api/withdraw', ['bank_account_id' => 1, 'amount' => 20001, 'reason' => 'test it']);
        $response
            ->assertStatus(403)
            ->assertExactJson([
                "code" => 403,
                "message" => "Exceeded maximum withdrawal amount per transaction"
            ]);

        $response = $this->json('POST', '/api/withdraw', ['bank_account_id' => 1, 'amount' => 89000, 'reason' => 'test it']);
        $response
            ->assertStatus(403)
            ->assertExactJson([
                "code" => 403,
                "message" => "Exceeded maximum withdrawal amount per transaction"
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_not_exceed_withdraw_transactions()
    {
        factory(Deposit::class)->times(3)->create(["amount" => 30000]); //Make sure there is money in account
        factory(Withdrawal::class)->times(2)->create(["amount" => 10000]); // Enter in database 2 withdrawal with total of 20k
        $response = $this->json('POST', '/api/withdraw', ['bank_account_id' => 1, 'amount' => 20000, 'reason' => 'test it']);
        $response
            ->assertStatus(201)
            ->assertJson([
                "amount" => 20000
            ]);

        $response = $this->json('POST', '/api/withdraw', ['bank_account_id' => 1, 'amount' => 20000, 'reason' => 'test it']);
        $response
            ->assertStatus(403)
            ->assertExactJson([
                "code" => 403,
                "message" => "Exceeded maximum withdrawal transactions allowed per day"
            ]);
    }

    /**
     * @test
     */
    public function a_user_can_not_withdraw_more_than_daily_limit()
    {
        factory(Deposit::class)->times(3)->create(["amount" => 30000]); //Make sure there is money in account
        factory(Withdrawal::class)->times(2)->create(["amount" => 20000]); // Enter in database 2 withdraws with total of 40k
        $response = $this->json('POST', '/api/withdraw', ['bank_account_id' => 1, 'amount' => 10001, 'reason' => 'test it']);
        $response
            ->assertStatus(403)
            ->assertExactJson([
                "code" => 403,
                "message" => "Exceeded maximum withdrawal amount per day"
            ]);
    }

    /**
     * @test
     */
    public function  a_user_can_not_withdraw_more_than_bank_balance()
    {
        factory(Deposit::class)->create(["amount" => 5000]); //Make sure there is money in account
        $response = $this->json('POST', '/api/withdraw', ['bank_account_id' => 1, 'amount' => 10001, 'reason' => 'clear balance']);
        $response
            ->assertStatus(403)
            ->assertExactJson([
                "code" => 403,
                "message" => "Cannot withdraw when balance is less than withdrawal amount"
            ]);
    }

    /**
     * @test
     */
    public function it_tests_middleware_maintains_correct_date()
    {
        // Deposits made yesterday exceed limits
        factory(Deposit::class)->times(4)->create(["amount" => 40000, "created_at" => date("Y-m-d", time() - 60 * 60 * 24)]);

        // Deposit made today should not be affected with deposits made previous day
        $response = $this->json('POST', '/api/deposit', ['bank_account_id' => 1, 'amount' => 30001, 'reason' => 'test it']);
        $response
            ->assertStatus(201)
            ->assertJson([
                "amount" => 30001
            ]);
    }
}
