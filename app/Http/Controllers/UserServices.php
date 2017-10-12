<?php
	/**
	 * Created by PhpStorm.
	 * User: dark-
	 * Date: 9/17/2017
	 * Time: 10:28 AM
	 */

	namespace App\Http\Controllers;
	use Laravel\Passport\Passport;
	use App\User;
	use Illuminate\Contracts\Pagination\Paginator;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Response;
	use Illuminate\Http\Response as IlluminateResponse;
	use Carbon\Carbon;
	use \Validator;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Response as IlluResponse;
	use Novent\Transformers\userTransfomer;
			class UserServices extends Controller
			{/**
			 * @var  Novent\Transformers\userTransfomer
			 */
				protected $userTrans;
				public function __construct (userTransfomer $userTrans )
				{
					$this->userTrans= $userTrans;
					//$this->middleware('auth.basic', ['only' => 'store']);

				}
				//HTML code 200
				const HTTP_OK = 200;
				const HTTP_CREATED = 201;
				const HTTP_ACCEPTED = 202;
				const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
				const HTTP_NO_CONTENT = 204;

				//HTML code 400
				const HTTP_BAD_REQUEST = 400;
				const HTTP_UNAUTHORIZED = 401;
				const HTTP_PAYMENT_REQUIRED = 402;
				const HTTP_FORBIDDEN = 403;
				const HTTP_NOT_FOUND = 404;

				//HTML code 500
				const HTTP_INTERNAL_SERVER_ERROR = 500;
				const HTTP_NOT_IMPLEMENTED = 501;
				const HTTP_BAD_GATEWAY = 502;
				const HTTP_SERVICE_UNAVAILABLE = 503;
				const HTTP_GATEWAY_TIMEOUT = 504;

				const success='success';
				const fail='fail';

				/**
				* @var int
				 */
				protected $statusCode=200;

				/**
				* @return  mixed
				 */
				public function getStatusCode()
				{
					return $this->statusCode;
				}


				/**
				 * @param Paginator $lessons
				 * @param $data
				 * @return mixed
				 */
				protected function respondWithPagnation(Paginator $lessons, $data)
				{
					$d=$data;
					//$d=array_count_values  ($data);
					$data=array_merge ($data,
						[
							'paginator'=>[
							'total_count'=> $lessons->Total(),
							'total_page' =>ceil($lessons->Total() / $lessons->perPage()),
							'Curant_page'=>$lessons->currentPage(),
							'limit'=>$lessons->perPage(),
					//		'object_array'=>$d
						]
						]);
					return $this->respond ($data);
				}

				/**
				* @param mixed $statusCode
				 * @return $this
				 */

				public function setStatusCode($statusCode)
				{
					 $this->statusCode=$statusCode;
					return $this;
				}
				/**
				* @param string $massage
				 * @return mixed
				 */

				public function respondNotFound($massage = 'Not Found !')
				{
						//return $this->setStatusCode (self::HTTP_NOT_FOUND)->respondWithError($massage);
						return $this->setStatusCode (self::HTTP_NOT_FOUND)->respondWithError($massage,'fail');
				}

				/**
				 * @param string $massage
				 * @return mixed
				 */
				public function respondInternalError($massage = 'Internal Error')
				{
					return $this->setStatusCode (self::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($massage);
				}


				/**
				* @param $data
				 * @param array $headers
				 * @return mixed
				 */


				public function respond($data,$headers=[])
				{
					return Response::json($data,$this->getStatusCode (),$headers);
				}

				public function status($status)
				{
					return $status;
				}

					/**
					 * @param $massage
					 * @return mixed
					 */
				public function respondWithError($massage,$status=null)
				{
					return $this->setStatusCode (self::HTTP_BAD_REQUEST)-> respond([

							'massage'=> $massage,
							'code'=>$this->statusCode
							,'status'=>$this->status ($status)

					]);
				}
				/**
				* @param $massage
				 * @return mixed
				 */
				public function responedCreated($massage,$status,$id)
				{
					return $this->setStatusCode (self::HTTP_CREATED) ->respond ([
						'massage'=>$massage,
						'status'=>$this->status ($status)
						,'code'=>$this->statusCode,
						'data'=>$id,
					]);
				}




				public function responedCreated200($massage,$status,$id=null)
				{
					return $this->setStatusCode (self::HTTP_OK) ->respond ([
						'massage'=>$massage,
						'id'=>$id,
						'status'=>$this->status ($status)
						,'code'=>$this->statusCode
					]);
				}
				public function responed_Destroy200($massage,$status)
				{
					return $this->setStatusCode (self::HTTP_OK) ->respond ([
						'massage'=>$massage,
						'status'=>$this->status ($status)
						,'code'=>$this->statusCode
					]);
				}

				public function responedFound200($massage,$status,$data)
				{
					return $this->setStatusCode (self::HTTP_OK) ->respond ([
						'massage'=>$massage,
						'status'=>$this->status ($status),
						'code'=>$this->statusCode,
							'size'=>count($data),
						'data'=>$data
					]);
				}

				public function responedFound200ForOneUser($massage,$status,$data)
				{
					return $this->setStatusCode (self::HTTP_OK) ->respond ([
						'massage'=>$massage,
						'status'=>$this->status ($status),
						'code'=>$this->statusCode,
//						'size'=>count($data),
						'data'=>$data
					]);
				}
				public function responedFound200ForOneUserToken($massage,$status,$data,$token)
				{
					return $this->setStatusCode (self::HTTP_OK) ->respond ([
						'massage'=>$massage,
						'status'=>$this->status ($status),
						'code'=>$this->statusCode,
						'data'=>$data,'token'=>$token,

					]);
				}
				public function respondwithdata($massage,$status,$data)
				{
					return $this->setStatusCode (self::HTTP_BAD_REQUEST) ->respond ([
						'massage'=>$massage,
						'status'=>$this->status ($status),
						'code'=>$this->statusCode,
						'data'=>$data
					]);
				}
				public function respondwithErrorMessage($status,$data)
				{
					return $this->setStatusCode (self::HTTP_BAD_REQUEST) ->respond ([
						'massage'=>$data,
						'status'=>$this->status ($status),
						'code'=>$this->statusCode,

					]);
				}

				public function getAllUser()
				{
					//		{
		//			$phone = Input::get ('phone');
		//			$user_phone =User::where('phone',$phone)->first ();
		//
		//			if($user_phone ==null and $phone==null)
		//			{
		//				{
		//
		//
		//					$users = User::all ();
		//
		//					/*return	IlluResponse::json([
		//						'data'=>$this->userTrans->transformCollection  ($users->all ())
		//					],200);*/
		//
		//					return UserServices:: responedFound200 ('users found', 'Passed', $this->userTrans->transformCollection ($users->all ()));
		//				}

		//			}
		//
		//
		//			else if($user_phone!==null)
		//				return $this->setStatusCode (200)->responedFound200 ('user found','pass',$this->userTrans->transform  ($user_phone));
		//			else
		//				return	$this->setStatusCode (404)->respondNotFound ('user not found');

					$users = User::where ('status',true)->get ();

					/*return	IlluResponse::json([
						'data'=>$this->userTrans->transformCollection  ($users->all ())
					],200);*/
//					dd($users->count ());
					return $this->responedFound200 ('users found', self::success, $this->userTrans->transformCollection  ($users->all ()));

				}


			public function get_one_user($id=null)
			{

			$users=User::where('id',$id)->where ('status',true)->first ();



				if(!$users)
				{
				return $this->respondNotFound('user dose not found');

			}
			//	if($id == $users->id or $pnum==$users->phone)

			/*	if(! $users)
				{
					$userarray = array(
					'id'=>$usernumber->id,
					'name'=>$usernumber->name,
					'email'=>$usernumber->email,
					'phone'=>$usernumber->phone,
					'created_at'=>$usernumber->created_at,
				//	'phone'=>$usernumber->phone,
					);
					/*return IlluResponse::json([
						'data'=>$this->userTrans->transform ($userarray)
					],200);*/
			//		return	`UserServices:: responedFound200('user found','Passed',$this->userTrans->transform ($userarray));
			//	}*/
			//	else
			//		if(!$usernumber)
			//				return IlluResponse::json([
			/*'data'=>$this->userTrans->transform ($users)*/
			return	$this-> responedFound200ForOneUser ('user found',self::success,$this->userTrans->transform  ($users));

			//				]);
			}

			public function create_user(Request $request)
			{
			/*$this->validate ($request,[
							'name'=>'required|max:3'
						]);*/

		//				$request= $this->checkRequestType();

			$rules = array (
				'name' => 'required|regex:/^[\p{L}\s\.-]+$/|min:3|max:30',
				'email' => 'required|email|unique:users',
				'phone' => 'required|phone:JO|unique:users',
		//				'phonefield' => 'phone:JO,BE,mobile',
				'password' => 'required|min:8|max:30'
			);
			$messages = array (
				'name.required' => 'The name is really really really important.',
				'name.min' => 'The name min is 3.',
				'name.max' => 'The name min is 30',
				'email.required' => 'The email is important for my life ',
				'email.email' => 'take your time and add Real email',
				'email.unique' => 'this email is already exiting',
				'phone.unique' => 'this phone number is already exiting',
				'phone.phone:JO' => ' enter phone number with jordan code 962',
				'phone.phone' => ' enter valid phone number such as 962785555555',
		//				'phone.phone:KS' => ' enter phone number with jordan code 966'
			);

			$validator = Validator::make ($request->all (), $rules, $messages);
		//			$errors= $validator;
			$errors = $validator->errors();


			if ($validator->fails ())
		//				return $this->setStatusCode (404)->respondwithErrorMessage (
		//					'some thing wrong', 'fail', $errors->first('name'));

				if($errors->first('name'))
					return $this->respondwithErrorMessage (
						self::fail,  $errors->first('name'));
			if ($errors->first('email'))
				return $this->respondwithErrorMessage (
					self::fail,  $errors->first('email'));
			if ($errors->first('phone'))
				return $this->respondwithErrorMessage (
					self::fail,  $errors->first('phone'));
			if ($errors->first('password'))
				return $this->respondwithErrorMessage (
					self::fail,  $errors->first('password'));



			else

		//				$email = DB::table ('users')->where ('phone', $request->input ('email'))->first ();
		//				$phone = DB::table ('users')->where ('phone', $request->input ('phone'))->first ();

				$user = User::create ([
					'name' => $request->input ('name'),
					'email' => $request->input ('email'),
					'phone' => $request->input ('phone'),
					'password' => bcrypt ($request->input ('password')),

				])->id;


			//return $this->responedCreated ('Lesson successfully Created !');
			return $this->responedCreated200 (' successfully Created !', self::success, $user);

			}


			public function delete_user($id)
			{
			$user = User::find ($id);
			if (!$user) {
				return $this ->respondWithError ('User for id:' . $id . ' is not Exiting',self::fail);
			} else {
				DB::table('users')
					->where('id', $id)
					->update( ['status' => false] );
				return $this->responed_Destroy200  ('user was deleted ',self::success);

			}



			}

			public function update_user(Request $request, $id)
			{
				$rules = array (
					'name' => 'regex:/^[\p{L}\s\.-]+$/|min:3|max:30',
					'email' => 'email|unique:users',
					'phone' => 'phone:JO|unique:users',
					//				'phonefield' => 'phone:JO,BE,mobile',
					'password' => 'required|min:8|max:30'
				);
				$messages = array (
					'name.regex' => 'please Enter Name with only real char',
					'name.min' => 'The name min is 3.',
					'name.max' => 'The name min is 30',
					'email.email' => 'take your time and add Real email',
					'email.unique' => 'this email is already exiting',
					'phone.unique' => 'this phone is already exiting',
					'phone.phone:JO' => ' enter phone number with jordan code 962',
					'phone.phone' => ' enter valid phone number',
					//				'phone.phone:KS' => ' enter phone number with jordan code 966'
				);

				$validator = Validator::make ($request->all (), $rules, $messages);
				//			$errors= $validator;
				$errors = $validator->errors();


				if ($validator->fails ())
					if($errors->first('name'))
						return $this->respondwithErrorMessage (
							self::fail,  $errors->first('name'));
				if ($errors->first('email'))
					return $this->respondwithErrorMessage (
						self::fail,  $errors->first('email'));
				if ($errors->first('phone'))
					return $this->respondwithErrorMessage (
						self::fail,  $errors->first('phone'));


			$findid = User::find($id);
			/*	DB::table('users')
							->where('id', $id)
							->update(['name' => $request->input('name'),
								'email' => $request->input('email'),
								'phone' => $request->input('phone')]);*/

			$name=$request->input('name');
			$email=$request->input('email');
			$phone=$request->input('phone');


			if(! $findid)
				return $this->respondWithError ('User not found ',self::fail);
			else {

				$new_name = DB::table('users')->where('id',$id)->first();
				//			var_dump($new_name->email);
				if($new_name->name !== $name and $name!==null)
				{
					DB::table('users')
						->where('id', $id)
						->update( ['name' => $request->input('name') ] );
				}

				if($new_name->email !== $email and $email !==null)
				{
					DB::table('users')
						->where('id', $id)
						->update( ['email' => $request->input('email') ] );
				}

				if($new_name->phone !== $phone and $phone !== null)
				{
					DB::table('users')
						->where('id', $id)
						->update( ['phone' => $request->input('phone') ] );
				}


				$data= User::find($id);

				return $this ->responedFound200ForOneUser ('user was updated',self::success,$this->userTrans->transform  ($data));

			}

			}
				public function get_phone_Query(Request $request)
				{


						$phone = $request->input ('phone');
						$user_phone = User::where ('phone', $phone)->where ('status', true)->first ();
						$user_phoneDeac = User::where ('phone', $phone)->where ('status', false)->first ();

						if ($user_phone == null and $user_phoneDeac == null)
							return $this->respondWithError ('user Not Found', self::fail);
						elseif ($user_phoneDeac)
							return $this->respondWithError ('this user is deactivate', self::fail);
						else
							return $this->responedFound200ForOneUser ('user found', self::success,
								$this->userTrans->transform ($user_phone));
					}


			public function get_one_user_date(Request $request)
			{

//				$date = Input::get ('date');



				$rules = array (
					'date' => 'required|date_format:Y-m-d',

				);
				$messages = array (
					'date.date_format'=>'enter valid for  2017-09-05',
					'date.required'=>'start date required for me',

				);

				$validator = Validator::make ($request->all  () , $rules, $messages);
				//			$errors= $validator;
				$errors = $validator->errors();

				if ($validator->fails ())
					if($errors->first('date'))
						return $this->respondwithErrorMessage (
							self::fail,  $errors->first('date'));
//				$date_after_formate= date("Y-m-d",strtotime(Input::get ('datee')));
//				$user= User::where('created_at',date("Y-m-d",strtotime(Input::get ('datee'))))->first ();
//			User::
				$user= User::whereDate('created_at', '=', date("Y-m-d",strtotime(Input::get ('date'))) )->first ();

				if (! $user)
					return $this->respondWithError ('user not found', self::fail);
				else    //if ($user !== null)
					return $this->responedFound200ForOneUser ('user found', self::success,
						$this->userTrans->transform  ($user));
			}


				public function get_date_Query(Request $request)
				{
					$rules = array (
						'start_date' => 'required|date_format:Y-m-d',
						'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
							'status'=>'required'
					);
					$messages = array (
						'start_date.date_format'=>'enter valid for  2017-09-05',
						'start_date.required'=>'start date required for me',
						'status.required'=>' status required',
						'end_date.date_format'=>'enter valid for end date   2017-09-05',
						'end_date.required'=>'end date required for me',
						'start_date.before'=>'start date must be before end date',
						'end_date.after'=>'end date must be after start date',
					);

					$validator = Validator::make ($request->all  () , $rules, $messages);
					//			$errors= $validator;
					$errors = $validator->errors();


					if ($validator->fails ())
						if($errors->first('start_date'))
							return $this->respondwithErrorMessage (
								self::fail,  $errors->first('start_date'));
					if($errors->first('end_date'))
						return $this->respondwithErrorMessage (
							self::fail,  $errors->first('end_date'));
					if($errors->first('status'))
						return $this->respondwithErrorMessage (
							self::fail,  $errors->first('status'));


					$start = date("Y-m-d",strtotime(Input::get ('start_date')));
					$end = date("Y-m-d",strtotime(Input::get ('end_date')."+1 day"));
						$user=User::whereBetween('created_at',[$start,$end])->where ('status',Input::get ('status'))->get ();
					if ($user->first () === null)
						return $this->respondWithError ('user not found', self::fail);
					else    //if ($user !== null)
						return $this->responedFound200 ('user found', self::success,
							$this->userTrans->transformCollection ($user->toArray ()));



				}

				/**
				 * @var $start_data mixed
				 * @var $end_data mixed
				 */
				/*public function getdate()
				{
					$start_data = Input::get ('startData');
					$end_data = Input::get ('endData');
		//			$user = User:: whereBetween ('created_at', [$start_data, $end_data])-get();
					$user = User::whereBetween('created_at', [$start_data->format('Y-m-d'), $end_data->format('Y-m-d')])->get();
		//			$user = User::whereBetween('created_at', [$start_data->format('Y-m-d')." 00:00:00", $dateE->format('Y-m-d')." 23:59:59"])->get();
		//				dd($user)
					var_dump($user);
					if ($user != null)
					return $this->setStatusCode (200)->responedFound200 ('user found','pass',
						$this->userTrans->transformCollection($user->all()));
		//			dd($user_phone['id']);


						else
						return $this->setStatusCode (400)->respondWithError ('user not found','fail');
				}*/

			}