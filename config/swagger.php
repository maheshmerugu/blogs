<?php
return[
 'users' => [
    'user_signup' =>[
  /**
    *  @OA\Post(
    *     path="/api/sign-up",
    *     tags={"User"},
    *     summary="User Signup",
    *     description="Multiple status values can be provided with comma separated string",
    *     operationId="sign_up",
    *     @OA\Parameter(
    *         name="name",
    *         in="query",
    *         description="Enter user name",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="Sunil Tiwari",
    *            
    *         )
    *     ),
    * @OA\Parameter(
    *         name="mobile",
    *         in="query",
    *         description="Enter user mobile number",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="7007979552",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="college_id",
    *         in="query",
    *         description="Enter college id",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="1",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="year_id",
    *         in="query",
    *         description="Enter year",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="integer",
    *             default="2022",
    *            
    *         )
    *     ),
    *  @OA\Parameter(
    *         name="email",
    *         in="query",
    *         description="Enter user email",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="tsunil870@gmail.com",
    *             
    *         )
    *     ),
    *   @OA\Parameter(
    *         name="password",
    *         in="query",
    *         description="Enter password",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *             default="12345678",
    *            
    *         )
    *     ),
    *    @OA\Parameter(
    *         name="device_id",
    *         in="query",
    *         description="Enter device id",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *            
    *            
    *            
    *         )
    *     ),
    *     @OA\Parameter(
    *         name="device_type",
    *         in="query",
    *         description="Enter device type",
    *         required=true,
    *         explode=true,
    *         @OA\Schema(
    *            
    *             type="string",
    *            
    *            
    *            
    *         )
    *     ),
    
    *     @OA\Response(
    *         response=200,
    *         description="successful operation",
    *        
    *     ),
    * ),
    
    * 
    * )
    */
],
]
];