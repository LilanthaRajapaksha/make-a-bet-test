<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BetCreationTest extends TestCase
{
    use RefreshDatabase;

    public function testStructureMismatchValidation()
    {
        $requestData = [];

        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [[
                    "code" => STRUCTURE_MISMATCH_ERROR_CODE,
                    "message" => STRUCTURE_MISMATCH_ERROR_MESSAGE
                ]],
                "selections" => []
            ]);
    }

    public function testMinStakeValidation()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "0",
            "selections" => [
                [
                    "id" => 10,
                    "odds" => "10"
                ],
                [
                    "id" => 11,
                    "odds" => "1.61"
                ],
                [
                    "id" => 1,
                    "odds" => "10"
                ]
            ]
        ];

        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [[
                    "code" => MIN_STAKE_AMOUNT_ERROR_CODE,
                    "message" => MIN_STAKE_AMOUNT_ERROR_MESSAGE.MIN_STAKE_AMOUNT
                ]],
                "selections" => []
            ]);
    }

    public function testMaxStakeValidation()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "100000000",
            "selections" => [
                [
                    "id" => 10,
                    "odds" => "10"
                ],
                [
                    "id" => 11,
                    "odds" => "1.61"
                ],
                [
                    "id" => 1,
                    "odds" => "10"
                ]
            ]
        ];

        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [[
                    "code" => MAX_STAKE_AMOUNT_ERROR_CODE,
                    "message" => MAX_STAKE_AMOUNT_ERROR_MESSAGE.MAX_STAKE_AMOUNT
                ]],
                "selections" => []
            ]);
    }

    public function testMinSelectionsValidation()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "100",
            "selections" => []
        ];

        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [[
                    "code" => MIN_SELECTIONS_ERROR_CODE,
                    "message" => MIN_SELECTIONS_ERROR_MESSAGE.MIN_SELECTIONS
                ]],
                "selections" => []
            ]);
    }

    public function testMaxSelectionsValidation()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "100",
            "selections" => [
                [
                    "id" => 1,
                    "odds" => "10"
                ],
                [
                    "id" => 2,
                    "odds" => "1.61"
                ],
                [
                    "id" => 3,
                    "odds" => "10"
                ],
                [
                    "id" => 4,
                    "odds" => "10"
                ],
                [
                    "id" => 5,
                    "odds" => "1.61"
                ],
                [
                    "id" => 6,
                    "odds" => "10"
                ],
                [
                    "id" => 7,
                    "odds" => "10"
                ],
                [
                    "id" => 8,
                    "odds" => "1.61"
                ],
                [
                    "id" => 9,
                    "odds" => "10"
                ],
                [
                    "id" => 10,
                    "odds" => "10"
                ],
                [
                    "id" => 11,
                    "odds" => "1.61"
                ],
                [
                    "id" => 12,
                    "odds" => "10"
                ],
                [
                    "id" => 13,
                    "odds" => "10"
                ],
                [
                    "id" => 14,
                    "odds" => "1.61"
                ],
                [
                    "id" => 15,
                    "odds" => "10"
                ],
                [
                    "id" => 16,
                    "odds" => "10"
                ],
                [
                    "id" => 17,
                    "odds" => "1.61"
                ],
                [
                    "id" => 18,
                    "odds" => "10"
                ],
                [
                    "id" => 19,
                    "odds" => "10"
                ],
                [
                    "id" => 20,
                    "odds" => "1.61"
                ],
                [
                    "id" => 21,
                    "odds" => "10"
                ]
            ]
        ];

        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [[
                    "code" => MAX_SELECTIONS_ERROR_CODE,
                    "message" => MAX_SELECTIONS_ERROR_MESSAGE.MAX_SELECTIONS
                ]],
                "selections" => []
            ]);
    }

    public function testMinOddValidation()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "100",
            "selections" => [
                [
                    "id" => 1,
                    "odds" => "0"
                ]
            ]
        ];
        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [],
                "selections" => [
                    [
                        "id" => 1,
                        "errors" => [
                            [
                                "code" => MIN_ODD_ERROR_CODE,
                                "message" => MIN_ODD_ERROR_MESSAGE.MIN_ODDS
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testMaxOddValidation()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "100",
            "selections" => [
                [
                    "id" => 1,
                    "odds" => "10000000"
                ]
            ]
        ];
        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [],
                "selections" => [
                    [
                        "id" => 1,
                        "errors" => [
                            [
                                "code" => MAX_ODD_ERROR_CODE,
                                "message" => MAX_ODD_ERROR_MESSAGE.MAX_ODDS
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testDuplicateSelectionValidation()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "100",
            "selections" => [
                [
                    "id" => 1,
                    "odds" => "10"
                ],
                [
                    "id" => 1,
                    "odds" => "10"
                ]
            ]
        ];
        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [],
                "selections" => [
                    [
                        "id" => 1,
                        "errors" => [
                            [
                                "code" => DUPLICATE_SELECTION_ERROR_CODE,
                                "message" => DUPLICATE_SELECTION_ERROR_MESSAGE
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testMaxWinAmountValidation()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "99",
            "selections" => [
                [
                    "id" => 1,
                    "odds" => "10000"
                ],
                [
                    "id" => 2,
                    "odds" => "10000"
                ]
            ]
        ];
        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [[
                    "code" => MAX_WIN_AMOUNT_ERROR_CODE,
                    "message" => MAX_WIN_AMOUNT_ERROR_MESSAGE.MAX_WIN_AMOUNT
                ]],
                "selections" => []
            ]);
    }

    public function testInsufficientBalanceValidation()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "3000",
            "selections" => [
                [
                    "id" => 1,
                    "odds" => "1"
                ],
                [
                    "id" => 2,
                    "odds" => "2"
                ]
            ]
        ];
        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [[
                    "code" => INS_BAL_ERROR_CODE,
                    "message" => INS_BAL_ERROR_MESSAGE
                ]],
                "selections" => []
            ]);
    }

    public function testSuccessRequest()
    {
        $requestData = [
            "player_id" => 1,
            "stake_amount" => "3",
            "selections" => [
                [
                    "id" => 1,
                    "odds" => "1"
                ],
                [
                    "id" => 2,
                    "odds" => "2"
                ]
            ]
        ];
        $this->json('POST', 'api/bet', $requestData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([]);
    }
}
