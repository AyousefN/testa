<?php

	namespace App;

	use Illuminate\Notifications\Notifiable;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Laravel\Passport\HasApiTokens;
	class User extends Authenticatable
	{
//    use Notifiable;
		use HasApiTokens, Notifiable;
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = [
			'name', 'email', 'password','phone'
		];

		/**
		 * The attributes that should be hidden for arrays.
		 *
		 * @var array
		 */
		protected $hidden = [
			'password', 'remember_token',
		];


		public function validation($id)
		{
			return [
				'name*'=>'required|max:3',
				'email*'=>'required'
			];

		}
	}
