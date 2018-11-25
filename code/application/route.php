<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//
//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//    \think\Route::
//
//];

use think\Route;

Route::delete('api/cache', 'api/SystemController/clearAllCache');

Route::post('api/user','api/UserController/getUser');

Route::get('api/banner/:id','api/BannerController/getIndexBanner');

Route::get('api/category','api/CategoryController/getCategory');

Route::get('api/theme/:id', 'api/ThemeController/getTheme');

Route::get('api/item/:id', 'api/ItemController/getItem');

Route::get('api/index_item', 'api/ItemController/getIndexItems');

Route::get('api/package/:id', 'api/PackageController/getPackage');

Route::get('api/notice/:id', 'api/NoticeController/getNotice');

Route::get('api/time', 'api/timeController/getTime');

Route::post('api/token/user', 'api/UserController/getToken');

Route::post('api/order/create', 'api/OrderController/createOrder');

Route::post('api/order/:id', 'api/OrderController/getOrderData');

Route::post('api/order_list/:status', 'api/OrderController/getOrderListByStatus');

Route::post('api/pay', 'api/PayController/wxPay');

Route::post('api/recharge', 'api/RechargeController/accountRecharge');

Route::get('api/discount_item', 'api/RechargeController/getDiscountItem');

Route::post('api/get_amount', 'api/RechargeController/getRechargeAmountById');

Route::post('api/get_phone', 'api/UserController/getPhone');

Route::post('api/update_phone', 'api/UserController/updatePhone');

Route::post('api/get_recharge_amounts', 'api/RechargeController/getAmounts');

Route::post('api/notify/recharge', 'api/PayController/reviceRechargeNotify');
Route::post('api/notify/order', 'api/PayController/reviceOrderNotify');
Route::post('api/notify/combo', 'api/PayController/reviceComboNotify');

Route::post('api/amount', 'api/AccountController/getAccount');

Route::post('api/test', 'api/TestController/index');

Route::get('api/combo_detail/:id', 'api/ComboController/getComboDetail');

Route::get('api/combo', 'api/ComboController/getCombo');

Route::get('api/referrer/:number', 'api/ReferrerController/checkReferrer');

Route::post('api/combo/create', 'api/ComboController/createComboOrder');

Route::post('api/combo_count', 'api/ComboController/getComboCount');

Route::post('api/combo_list', 'api/ComboController/getComboList');

Route::post('api/combo_record', 'api/ComboController/getComboRecord');

Route::get('api/checkVip', 'api/UserController/checkVip');

Route::post('api/check_balance/:count', 'api/AccountController/checkBalance');

Route::post('api/account_reduce', 'api/AccountController/reduceAccountBalance');

Route::post('api/combo_show', 'api/ComboController/showComboList');

Route::post('api/reduce_combo_list', 'api/ComboController/reduceComboList');

Route::post('api/code', 'api/UserController/getCode');

Route::get('api/city', 'api/BusinessController/getCityListByManage');

Route::post('api/business', 'api/BusinessController/getBusinessByCity');
Route::get('api/business_item/:id', 'api/BusinessController/getBusinessItemsById');

Route::post('api/check_code', 'api/UserController/checkCode');

Route::get('api/manage_recharge', 'api/UserController/getRechargeOrderByReferrer');

Route::get('api/manage_combo', 'api/UserController/getComboRechargeOrderByReferrer');

Route::get('api/check_referrer', 'api/UserController/checkReferrer');

Route::get('api/key', 'api/CardController/checkCard');

Route::get('api/card_count', 'api/CardController/getCardTypeCount');

Route::get('api/card_detail/:type', 'api/CardController/getOneTypeDetails');

Route::get('api/card_use/:item', 'api/CardController/getCardByUseAndItem');

/*manage*/
Route::post('api/manage/login', 'api/ManageController/login');
Route::post('api/manage/user', 'api/UserController/getUserByManage');
Route::post('api/manage/order', 'api/OrderController/getOrderByManage');
Route::post('api/manage/check_status', 'api/ManageController/checkStatus');
Route::post('api/manage/recharge', 'api/RechargeController/getRechargeByManage');
Route::post('api/manage/combo', 'api/ComboController/getComboByManage');
Route::post('api/manage/item', 'api/ItemController/getItemByManage');
Route::post('api/manage/item/change_sort', 'api/ItemController/changeItemSort');
Route::post('api/manage/upload', 'api/UploadController/upload');
Route::post('api/manage/oneitem', 'api/ItemController/getOneItemByManage');
Route::post('api/manage/update_item', 'api/ItemController/updateItemByManage');
Route::post('api/manage/add_item', 'api/ItemController/addItemByManage');
Route::post('api/manage/delete_item', 'api/ItemController/deleteItemByManage');
Route::post('api/manage/combo_manage', 'api/ComboController/getCombosByManage');
Route::post('api/manage/add_combo', 'api/ComboController/addComboByManage');
Route::post('api/manage/onecombo', 'api/ComboController/getOneComboByManage');
Route::post('api/manage/update_combo', 'api/ComboController/updateComboByManage');
Route::post('api/manage/delete_combo', 'api/ComboController/deleteComboByManage');
Route::post('api/manage/banner_manage', 'api/bannerController/getBannerItemsByManage');
Route::post('api/manage/get_banners', 'api/bannerController/getBannersByManage');
Route::post('api/manage/add_banner', 'api/bannerController/addBannerByManage');
Route::post('api/manage/onebanner', 'api/bannerController/getOneBannerItemByManage');
Route::post('api/manage/update_banner', 'api/bannerController/updateBannerItemByManage');
Route::post('api/manage/delete_banner', 'api/bannerController/deleteBannerItemByManage');
Route::post('api/manage/theme', 'api/ThemeController/getThemeItemsByManage');
Route::post('api/manage/theme/change_sort', 'api/ThemeController/changeThemeSort');
Route::post('api/manage/get_theme_category', 'api/ThemeController/getThemesByManage');
Route::post('api/manage/add_theme_item', 'api/ThemeController/addThemeItemByManage');
Route::post('api/manage/get_theme_item', 'api/ThemeController/getOneThemeItemByManage');
Route::post('api/manage/update_theme_item', 'api/ThemeController/updateThemeItemByManage');
Route::post('api/manage/delete_theme_item', 'api/ThemeController/deleteThemeItemByManage');
Route::get('api/manage/package_items', 'api/PackageController/getPackageItemsByManage');
Route::post('api/manage/package', 'api/PackageController/getPackagesByManage');
Route::post('api/manage/add_package_item', 'api/PackageController/addPackageItemByManage');
Route::post('api/manage/get_package_item', 'api/PackageController/getOnePackageItemByManage');
Route::post('api/manage/update_package_item', 'api/PackageController/updatePackageItemByManage');
Route::post('api/manage/delete_package_item', 'api/PackageController/deletePackageItemByManage');
Route::get('api/manage/business', 'api/BusinessController/getBusinessListByManage');
Route::get('api/manage/city', 'api/BusinessController/getCityListByManage');
Route::post('api/manage/add_business', 'api/BusinessController/addBusinessByManage');
Route::post('api/manage/delete_business', 'api/BusinessController/deleteBusinessByManage');
Route::post('api/manage/one_business', 'api/BusinessController/getOneBusinessByManage');
Route::post('api/manage/update_business', 'api/BusinessController/updateBusinessByManage');
Route::get('api/manage/business_item', 'api/BusinessController/getBusinessItemsByManage');
Route::get('api/manage/one_business_item', 'api/BusinessController/getOneBusinessItemByManage');
Route::put('api/manage/business_item', 'api/BusinessController/updateBusinessItemByManage');
Route::post('api/manage/business_item', 'api/BusinessController/addBusinessItemByManage');
Route::delete('api/manage/business_item', 'api/BusinessController/deleteBusinessItemByManage');