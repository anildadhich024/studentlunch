<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('', 'HomeController@IndexPage');
Route::get('terms-and-conditions', 'HomeController@PageTrms');
Route::get('privacy-policy', 'HomeController@PagePrvcy');
Route::get('registration/parent', 'ParentController@IndexPage');
Route::post('registration/parent/save', 'ParentController@SaveCntrl');
Route::get('registration/milkbar', 'MilkbarController@IndexPage');
Route::post('registration/milkbar/save', 'MilkbarController@SaveCntrl');
Route::get('registration/teacher', 'TeacherController@IndexPage');
Route::post('registration/teacher/save', 'TeacherController@SaveCntrl');
Route::get('user/login', 'LoginController@IndexPage');
Route::post('user/authenticate', 'LoginController@Login');
Route::get('logout', 'LoginController@Logout');
Route::get('user/forgot', 'PasswordController@ForgotPassword');
Route::post('user/send_otp', 'PasswordController@SendCode');
Route::get('user/verify', 'PasswordController@VerifyOtp');
Route::post('user/reset_password', 'PasswordController@ResetPass');


Route::group(['middleware' => 'prevent-back-history'], function()
{
	Auth::routes();
    Route::namespace('AdminController')->group(function () {
    	
    	// Manage Account Details
		Route::get('admin_panel/manage_account', 'AccountController@IndexPage');
		Route::post('admin_panel/manage_account/save', 'AccountController@SaveCntrl');

    	Route::get('admin_panel/forgot_password', 'HomeController@FrgtPass');
    	Route::post('admin_panel/forgot_password/email', 'HomeController@FrgtEmail');
    	Route::get('admin_panel/reset_password', 'HomeController@RstPass');
    	Route::post('admin_panel/reset_password/save', 'HomeController@SavePass');


    	// Manage Dashboard
    	Route::get('admin_panel', 'HomeController@IndexPage');
    	Route::post('admin_panel/login', 'HomeController@LoginPage');
    	Route::get('admin_panel/logout', 'HomeController@Logout');
    
    	// Manage Country
    	Route::post('admin_panel/country/save', 'CountryController@SaveCntrl');
    	Route::get('admin_panel/country/list', 'CountryController@ListPage');

    	// Manage Plan
    	Route::post('admin_panel/plan/save', 'PlanController@SaveCntrl');
    	Route::get('admin_panel/plan/list', 'PlanController@ListPage');
    	Route::get('admin_panel/plan/active', 'PlanController@ActvPlan');

		//Manage Subscription 
		Route::get('admin_panel/manage_subscription', 'PlanController@SmryPage');
		Route::get('admin_panel/subscription/list', 'PlanController@ListSub'); 

    	// Manage State
    	Route::post('admin_panel/state/save', 'StateController@SaveCntrl');
    	Route::get('admin_panel/state/list', 'StateController@ListPage');

		// Manage Holiday
    	Route::post('admin_panel/holiday/save', 'HolidayController@SaveCntrl');
    	Route::get('admin_panel/holiday/list', 'HolidayController@ListPage');

    	// Manage Milk Bar
    	Route::get('admin_panel/milk_bar/manage', 'MilkBarController@IndexPage');
    	Route::post('admin_panel/milk_bar/save', 'MilkBarController@SaveCntrl');
    	Route::get('admin_panel/milk_bar/list', 'MilkBarController@ListPage');
    	Route::get('admin_panel/milk_bar/detail', 'MilkBarController@DetailPage');
    	Route::get('admin_panel/milk_bar/active', 'MilkBarController@ActvStatus');
    	Route::get('admin_panel/milk_bar/export', 'MilkBarController@ExprtRcrd');
    	
    	// Manage Parent
    	Route::get('admin_panel/parent/list', 'ParentController@ListPage');
    	Route::get('admin_panel/parent/detail', 'ParentController@DetailPage');
    	Route::get('admin_panel/parent/export', 'ParentController@ExprtRcrd');

    	// Manage Teacher
    	Route::get('admin_panel/teacher/list', 'TeacherController@ListPage');
    	Route::get('admin_panel/teacher/detail', 'TeacherController@DetailPage');
    	Route::get('admin_panel/teacher/export', 'TeacherController@ExprtRcrd');
    	
    	// Manage School
    	Route::get('admin_panel/school/manage', 'SchoolController@IndexPage');
    	Route::post('admin_panel/school/save', 'SchoolController@SaveCntrl');
    	Route::get('admin_panel/school/list', 'SchoolController@ListPage');
    	Route::get('admin_panel/school/detail', 'SchoolController@DetailPage');
    	Route::get('admin_panel/school/grid', 'SchoolController@GridDtl');
    	Route::get('admin_panel/school/export', 'SchoolController@ExprtRcrd');
		
		// Manage School 
		Route::get('admin_panel/request/school/list', 'SchoolController@ReqList'); 
		Route::get('admin_panel/request_list/change_status', 'SchoolController@ChangeStatus'); 
		
    	// Manage Passowrd
		Route::get('admin_panel/change_password', 'AccountController@PswrdPage');
		Route::post('admin_panel/change_password/save', 'AccountController@PswrdCntrl');
		
		//Manage order
		Route::get('admin_panel/manage_order', 'OrderController@ListPage');
		Route::get('admin_panel/manage_order/export', 'OrderController@ExprtRcrd');
		
		//Manage Comission
		Route::get('admin_panel/manage_commission', 'CommissionController@SmryPage');
		Route::get('admin_panel/manage_commission_list', 'CommissionController@ListPage');
		Route::get('admin_panel/manage_commission/export', 'CommissionController@ExprtRcrd');
		Route::get('admin_panel/manage_commission_list/export', 'CommissionController@ExprtLst');

		// Manage Setting
		Route::post('admin_panel/setting/save', 'SettingController@SaveCntrl');
		Route::get('admin_panel/manage/settings', 'SettingController@SettingCntrl');
		Route::post('admin_panel/manage/settings', 'SettingController@SettingCntrl');

		// Manage Log File
		Route::get('admin_panel/manage/logFiles', 'SettingController@LogFilesCntrl'); 
		Route::get('admin_panel/logFiles/delete/{name}', 'SettingController@LogFilesDlt');
		
    });
    
	Route::namespace('ParentController')->group(function () {

		// Manage Dashboard
		Route::get('parent_panel', 'HomeController@IndexPage');

		//request school
		Route::post('parent_panel/school/save', 'AccountController@ParentSchCntrl');


		// Manage Account Details
		Route::get('parent_panel/manage_account', 'AccountController@IndexPage');
		Route::post('parent_panel/manage_account/save', 'AccountController@SaveCntrl');
		Route::post('parent_panel/purchases/subscription', 'AccountController@PayPlan');
		Route::get('parent_panel/purchases/confirm', 'AccountController@PayConfirm');

		// Manage Passowrd
		Route::get('parent_panel/change_password', 'AccountController@PswrdPage');
		Route::post('parent_panel/change_password/save', 'AccountController@PswrdCntrl');

		// Manage Plan
		Route::get('parent_panel/manage_subscription', 'SubscriptionController@IndexPage');
		Route::get('parent_panel/manage_subscription/free', 'SubscriptionController@FreePln');
		Route::post('parent_panel/manage_subscription/paid', 'SubscriptionController@PaidPln');
      
      	// Manage Order
		Route::get('parent_panel/place_order', 'OrderController@IndexPage');
		Route::get('parent_panel/get_order/child', 'OrderController@ChldOrd');
		Route::get('parent_panel/get_milk', 'OrderController@GetMlk');
		Route::post('parent_panel/place_order/save', 'OrderController@SaveOrder');
		Route::get('parent_panel/review_order', 'OrderController@RvwOrder');
		Route::get('parent_panel/checkout', 'OrderController@Checkout');
		Route::post('parent_panel/checkout', 'OrderController@CheckoutPost')->name('checkout.post');
		Route::post('parent_panel/checkoutcr', 'OrderController@CheckoutCrPost')->name('checkout.crpost');

		// Manage Order
		Route::get('parent_panel/self/place_order', 'SelfOrderController@IndexPage');
		Route::get('parent_panel/self/get_milk', 'SelfOrderController@GetMlk');
		Route::post('parent_panel/self/place_order/save', 'SelfOrderController@SaveOrder');
		Route::get('parent_panel/self/review_order', 'SelfOrderController@RvwOrder');
		Route::get('parent_panel/self/checkout', 'SelfOrderController@Checkout');
		Route::post('parent_panel/self/checkout/stripe', 'SelfOrderController@CheckoutPost')->name('checkout.parent.self.stripe');
		Route::post('parent_panel/self/checkout/credit', 'SelfOrderController@CheckoutCrPost')->name('checkout.parent.self.credit');
      
		//Manage order
		Route::get('parent_panel/manage_order', 'OrderController@ListOrder');
		Route::get('parent_panel/cancel_order', 'OrderController@CancelOrder');
		Route::get('parent_panel/manage_order/export', 'OrderController@ExprtRcrd');
		
		//credits
		Route::get('parent_panel/my_credits', 'CreditsController@IndexPage');
		Route::get('parent_panel/my_credits/export', 'CreditsController@ExprtRcrd');
	});

	Route::namespace('MilkBarController')->group(function () {

		// Manage Dashboard
		Route::get('milkbar_panel', 'HomeController@IndexPage');

		//request school
		Route::post('milkbar_panel/school/save', 'AccountController@MilkSchCntrl');

		// Manage Account Details
		Route::get('milkbar_panel/manage_account', 'AccountController@IndexPage');
		Route::get('milkbar_panel/stripe', 'AccountController@StripePage');
		Route::get('milkbar_panel/stripe/account', 'AccountController@StripeAcc');
		Route::post('milkbar_panel/my_account/save', 'AccountController@SaveCntrl');

		// Manage Passowrd
		Route::get('milkbar_panel/change_password', 'AccountController@PswrdPage');
		Route::post('milkbar_panel/change_password/save', 'AccountController@PswrdCntrl');

		// Manage Category
		Route::get('milkbar_panel/category/list', 'CategoryController@ListPage');
		Route::get('milkbar_panel/category/export', 'CategoryController@ExprtRcrd');
		Route::post('milkbar_panel/category/save', 'CategoryController@SaveCntrl');
		
		// Manage Menu
		Route::get('milkbar_panel/item/list', 'ItemController@ListPage');
		Route::get('milkbar_panel/item/export', 'ItemController@ExprtRcrd');
		Route::post('milkbar_panel/item/save', 'ItemController@SaveCntrl');
		Route::get('milkbar_panel/manage/item', 'ItemController@IndexPage');
		Route::post('milkbar_panel/item/import', 'ItemController@ImportCntrl');

		// Manage Variant
		Route::get('milkbar_panel/variant/list', 'VariantController@ListPage');
		Route::get('milkbar_panel/variant/export', 'VariantController@ExprtRcrd');
		Route::post('milkbar_panel/variant/save', 'VariantController@SaveCntrl');
		Route::post('milkbar_panel/variant/import', 'VariantController@ImportCntrl');
		Route::get('record/variant/delete', 'VariantController@DelRec');
		
		// Manage Credits
		Route::get('milkbar_panel/my_credits', 'CreditsController@ListPage');
		Route::get('milkbar_panel/my_credits/export', 'CreditsController@ExprtRcrd');

		// Manage Order
		Route::get('milkbar_panel/my_orders', 'OrderController@ListPage');
		Route::get('milkbar_panel/my_orders/deliverd', 'OrderController@DelvOrd');
		Route::get('milkbar_panel/my_orders/export', 'OrderController@ExprtRcrd');
		Route::get('milkbar_panel/my_orders/ticket', 'OrderController@TcktLst');
	});

	Route::namespace('TeacherController')->group(function () {

		// Manage Dashboard
		Route::get('teacher_panel', 'HomeController@IndexPage');

		//request school
		Route::post('teacher_panel/school/save', 'AccountController@TchSchCntrl');

		// Manage Account Details
		Route::get('teacher_panel/manage_account', 'AccountController@IndexPage');
		Route::post('teacher_panel/manage_account/save', 'AccountController@SaveCntrl');
		Route::post('teacher_panel/purchases/subscription', 'AccountController@PayPlan');
		Route::get('teacher_panel/purchases/confirm', 'AccountController@PayConfirm');

		// Manage Passowrd
		Route::get('teacher_panel/change_password', 'AccountController@PswrdPage');
		Route::post('teacher_panel/change_password/save', 'AccountController@PswrdCntrl');
      
      	// Manage Order
		Route::get('teacher_panel/place_order', 'OrderController@IndexPage');
		Route::get('teacher_panel/get_milk', 'OrderController@GetMlk');
		Route::post('teacher_panel/place_order/save', 'OrderController@SaveOrder');
		Route::get('teacher_panel/review_order', 'OrderController@RvwOrder');
		Route::get('teacher_panel/checkout', 'OrderController@Checkout');
		Route::post('teacher_panel/checkout/stripe', 'OrderController@CheckoutPost')->name('checkout.teacher.stripe');
		Route::post('teacher_panel/checkout/credit', 'OrderController@CheckoutCrPost')->name('checkout.teacher.credit');
      
		//Manage order
		Route::get('teacher_panel/manage_order', 'OrderController@ListOrder');
		Route::get('teacher_panel/cancel_order', 'OrderController@CancelOrder');
		Route::get('teacher_panel/manage_order/export', 'OrderController@ExprtRcrd');
		
		//credits
		Route::get('teacher_panel/my_credits', 'CreditsController@IndexPage');
		Route::get('teacher_panel/my_credits/export', 'CreditsController@ExprtRcrd');
	});
});

Route::namespace('CommonController')->group(function () {

	// Get State
	Route::get('get_state', 'AjaxController@StateLst');
	
	// Chnage Status
	Route::get('record/change_status', 'CommonController@ChngStatus');

	// Delete Record
	Route::get('record/delete', 'CommonController@DelRec');

	// Verify Email
	Route::get('account/verify', 'CommonController@EmailVrfy');
	
	// Order Detail
	Route::get('order/detail', 'CommonController@OrderDtl');

	// School List
	Route::get('get_school/list', 'AjaxController@SchlLst');

	// School Request
	Route::post('request/school', 'CommonController@SaveReqSch');

	// Menu Listing
	Route::get('get_menu/list', 'CommonController@GetMenu');

	// Menu Variant
	Route::get('get_varient/list', 'CommonController@GetVarnt');

	Route::get('register/session', 'CommonController@SaveData');

	// Auto Debit Stripe
	Route::get('parents/auto_debit', 'CommonController@AutoDebit');

	// SESSION MENU
	Route::post('user/save_cart', 'CommonController@SaveCart');

	// GET CART DATA
	Route::get('get_cart/data', 'CommonController@CartData');
	
	// REMOVE DATA FROM CART
	Route::get('cart_item/remove', 'CommonController@RemoveCart');

	// HOLIDAY DETAIL
	Route::get('get_holiday/detail', 'CommonController@HolidayDtl');
	

});