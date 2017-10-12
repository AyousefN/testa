<?php
	namespace App\Http\Controllers\api;

	use App\Http\Controllers\UserServices;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Auth;
	use App\User;
	use Illuminate\Support\Facades\Input;
	use Response;
	use \Validator;
//	use Illuminate\Http\Request;
	use Novent\Transformers\userTransfomer;





	class UserController extends UserServices
	{
		/**
	 * @var  Novent\Transformers\userTransfomer
	 */
		protected $userTrans;

		public function __construct(userTransfomer $userTrans){
			$this->userTrans= $userTrans;

			$this->content = array();

			$this->middleware ('auth:api')->except ('login');


					}
		public function index()
		{

			return $this->getAllUser ();

		}


		/**
		 * @param null $id
		 * @return mixed
		 */
		public function show($id=null)
		{

			return $this->get_one_user ($id);

		}

		public function store(Request $request)
		{

			return $this->create_user ($request);

		}


		/**
		 * @param $id
		 * @return mixed
		 */
		public function destroy ($id)
		{

			return $this->delete_user ($id);

		}

		/**
		 * @param Request $request
		 * @param $id
		 * @return mixed
		 */
		public function update(Request $request, $id)
		{
			return $this->update_user ($request, $id);

		}

		/**
		 * @return mixed
		 */
		public function get_phone( Request $request )
		{
//				if($request->has ('phone'))
			return $this->get_phone_Query ($request);

		}


		/**
		 * @return mixed
		 */
		public function get_date(Request $request)
		{

			return $this->get_date_Query ( $request);

		}

		public function get_user_date()
		{
			return $this->get_user_date ();
		}
		public function get_user_by_date(Request $request)
		{
			return $this->get_one_user_date ($request);
		}

		public function login(){
			if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
				$user = Auth::user();

				$this->content['token'] =  $user->createToken('Pizza App')->accessToken;
				$status = 200;
			}
			else{
				$this->content['error'] = "something wrong ";
				$status = 401;
			}
			return response()->json($this->content, $status);
		}

		public function details(){

			return $this->responedFound200ForOneUser ('user found',self::success,$this->userTrans->transform  (Auth::user()));
		}
	}