<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// symlink('/home/banme/domains/banme.com/laravel/storage/app/public', '/home/banme/domains/banme.com/public_html/storage');
// Route::get('/', function () {
//     return view('frontend.index');
// });
////front end section
 
Route::post('theme_update',[\App\Http\Controllers\Frontend\IndexController::class,'themeUpdate'])->name('front.theme.update');

Route::post('comment_save',[\App\Http\Controllers\Frontend\BlogController::class,'blogSaveComment'])->name('front.comment.save');
Route::get('/', [App\Http\Controllers\Frontend\IndexController::class, 'home'])->name('home');
Route::get('/home2', [App\Http\Controllers\Frontend\IndexController::class, 'home2'])->name('home2');
/////product
Route::get('product/search',[\App\Http\Controllers\Frontend\ProductController::class,'productSearch'])->name('front.product.search');
Route::get('product/hot',[\App\Http\Controllers\Frontend\ProductController::class,'productHot'])->name('front.product.hot');
Route::get('product/view/{id}',[\App\Http\Controllers\Frontend\ProductController::class,'productView'])->name('front.product.view');
Route::get('product/category/{slug}',[\App\Http\Controllers\Frontend\ProductController::class,'categoryView'])->name('front.product.cat');
/////blog
Route::post('page_search',[\App\Http\Controllers\Frontend\BlogController::class,'pageSearch'])->name('front.page.search');
Route::get('category/{slug}',[\App\Http\Controllers\Frontend\BlogController::class,'categoryView'])->name('front.category.view');
Route::get('page/{slug}',[\App\Http\Controllers\Frontend\BlogController::class,'pageView'])->name('front.page.view');
Route::get('chinhsach/{slug}',[\App\Http\Controllers\Frontend\BlogController::class,'chinhsachView'])->name('front.chinhsach.view');
Route::get('categories',[\App\Http\Controllers\Frontend\BlogController::class,'allCategoryView'])->name('front.categories.view');
Route::get('tags/{slug}',[\App\Http\Controllers\Frontend\BlogController::class,'tagView'])->name('front.tag.view');
///auth
Route::get('front/login', [App\Http\Controllers\Frontend\IndexController::class, 'viewLogin'])->name('front.login');    
Route::post('front/login', [App\Http\Controllers\Frontend\IndexController::class, 'login'])->name('front.login');    
Route::get('front/register', [App\Http\Controllers\Frontend\IndexController::class, 'viewRegister'])->name('front.register'); 
Route::post('front/register', [App\Http\Controllers\Frontend\IndexController::class, 'saveUser'])->name('front.register'); 
//////profile
Route::get('front/profile', [App\Http\Controllers\Frontend\ProfileController::class, 'viewDasboard'])->name('front.profile'); 
Route::post('front/profile/changepassword', [App\Http\Controllers\Frontend\ProfileController::class, 'changePassword'])->name('front.profile.changepass'); 
Route::get('front/profile/edit', [App\Http\Controllers\Frontend\ProfileController::class, 'createEdit'])->name('front.profile.edit'); 
Route::post('front/profile/edit', [App\Http\Controllers\Frontend\ProfileController::class, 'updateProfile'])->name('front.profile.edit'); 
Route::post('front/profile/addinvoiceadd', [App\Http\Controllers\Frontend\ProfileController::class, 'addInvoice'])->name('front.profile.addinvoiceadd'); 
Route::post('front/profile/addshipadd', [App\Http\Controllers\Frontend\ProfileController::class, 'addShip'])->name('front.profile.addshipadd'); 
Route::post('front/profile/updatetax', [App\Http\Controllers\Frontend\ProfileController::class, 'updateTax'])->name('front.profile.updatetax'); 
Route::post('front/profile/updatedescription', [App\Http\Controllers\Frontend\ProfileController::class, 'updateDescription'])->name('front.profile.updatedescription'); 
Route::post('front/profile/updatename', [App\Http\Controllers\Frontend\ProfileController::class, 'updateName'])->name('front.profile.updatename'); 
Route::get('front/profile/order', [App\Http\Controllers\Frontend\ProfileController::class, 'viewOrder'])->name('front.profile.order'); 
Route::get('front/profile/addressbook', [App\Http\Controllers\Frontend\ProfileController::class, 'addressbook'])->name('front.profile.addressbook'); 
Route::post('front/profile/update', [App\Http\Controllers\Frontend\ProfileController::class, 'updateProfile'])->name('front.profile.update'); 
Route::post('avatar-upload', [\App\Http\Controllers\Frontend\FilesController::class, 'avartarUpload' ])->name('front.upload.avatar');
////
Route::get('front/address/delete/{id}', [App\Http\Controllers\Frontend\ProfileController::class, 'deleteAddress'])->name('front.address.delete'); 
Route::get('front/address/setinvoice', [App\Http\Controllers\Frontend\ProfileController::class, 'setDefaultInvoice'])->name('front.address.setinvoice'); 
Route::get('front/address/setship', [App\Http\Controllers\Frontend\ProfileController::class, 'setDefaultShip'])->name('front.address.setship'); 


/////wishlist
Route::post('front/wishlist/add', [App\Http\Controllers\Frontend\WishListController::class, 'add'])->name('front.wishlist.add'); 
Route::get('front/wishlist/remove/{id}', [App\Http\Controllers\Frontend\WishListController::class, 'remove'])->name('front.wishlist.remove'); 

Route::get('front/wishlist/view', [App\Http\Controllers\Frontend\ProfileController::class, 'viewWishlist'])->name('front.wishlist.view'); 
/////wishlist
Route::post('front/shopingcart/add', [App\Http\Controllers\Frontend\ShopingCartController::class, 'add'])->name('front.shopingcart.add'); 
Route::get('front/shopingcart/view', [App\Http\Controllers\Frontend\ShopingCartController::class, 'viewCart'])->name('front.shopingcart.view'); 
Route::get('front/shopingcart/getlist', [App\Http\Controllers\Frontend\ShopingCartController::class, 'getList'])->name('front.shopingcart.getlist'); 
Route::post('front/shopingcart/update', [App\Http\Controllers\Frontend\ShopingCartController::class, 'update'])->name('front.shopingcart.update'); 
Route::get('front/shopingcart/checkout', [App\Http\Controllers\Frontend\ShopingCartController::class, 'checkout'])->name('front.shopingcart.checkout'); 
Route::post('front/shopingcart/order', [App\Http\Controllers\Frontend\ShopingCartController::class, 'order'])->name('front.shopingcart.order'); 
//contact
Route::get('front/contact', [App\Http\Controllers\Frontend\IndexController::class, 'contact'])->name('front.contact'); 
Route::post('front/contact/send', [App\Http\Controllers\Frontend\IndexController::class, 'savecontact'])->name('front.contact.save'); 



// viewDasboard
/////
// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes(['register'=>false]);

Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('admin1');

//Admin dashboard

Route::group( ['prefix'=>'admin/','middleware'=>'auth' ],function(){
    Route::get('/',[ \App\Http\Controllers\AdminController::class,'admin'])->name('admin');
    
    Route::middleware(['manager'])->group(function () {
    ///Banner section
    Route::resource('banner', \App\Http\Controllers\BannerController::class);
    Route::post('banner_status',[\App\Http\Controllers\BannerController::class,'bannerStatus'])->name('banner.status');
    Route::get('banner_search',[\App\Http\Controllers\BannerController::class,'bannerSearch'])->name('banner.search');
    ///tag section
    Route::resource('tag', \App\Http\Controllers\TagController::class);
    Route::post('tag_status',[\App\Http\Controllers\TagController::class,'tagStatus'])->name('tag.status');
    Route::get('tag_search',[\App\Http\Controllers\TagController::class,'tagSearch'])->name('tag.search');
    ///Role section
    Route::resource('role', \App\Http\Controllers\RoleController::class);
    Route::post('role_status',[\App\Http\Controllers\RoleController::class,'roleStatus'])->name('role.status');
    Route::get('role_search',[\App\Http\Controllers\RoleController::class,'roleSearch'])->name('role.search');
    Route::get('role_function\{id}',[\App\Http\Controllers\RoleController::class,'roleFunction'])->name('role.function');
    Route::get('role_selectall\{id}',[\App\Http\Controllers\RoleController::class,'roleSelectall'])->name('role.selectall');
    
    Route::post('functionstatus',[\App\Http\Controllers\RoleController::class,'roleFucntionStatus'])->name('role.functionstatus');
    
    
    ///cfunction section
    Route::resource('cmdfunction', \App\Http\Controllers\CFunctionController::class);
    Route::post('cmdfunction_status',[\App\Http\Controllers\CFunctionController::class,'cmdfunctionStatus'])->name('cmdfunction.status');
    Route::get('cmdfunction_search',[\App\Http\Controllers\CFunctionController::class,'cmdfunctionSearch'])->name('cmdfunction.search');

    ///Category section
    Route::resource('category', \App\Http\Controllers\CategoryController::class);
    Route::post('category_status',[\App\Http\Controllers\CategoryController::class,'categoryStatus'])->name('category.status');
    Route::get('category_search',[\App\Http\Controllers\CategoryController::class,'categorySearch'])->name('category.search');
    ///BlogCategory section
    Route::resource('blogcategory', \App\Http\Controllers\BlogCategoryController::class);
    Route::post('blogcategory_status',[\App\Http\Controllers\BlogCategoryController::class,'blogcatStatus'])->name('blogcategory.status');
    Route::get('blogcategory_search',[\App\Http\Controllers\BlogCategoryController::class,'blogcatSearch'])->name('blogcategory.search');
    ///Blog section
    Route::resource('blog', \App\Http\Controllers\BlogController::class);
    Route::post('blog_status',[\App\Http\Controllers\BlogController::class,'blogStatus'])->name('blog.status');
    Route::get('blog_search',[\App\Http\Controllers\BlogController::class,'blogSearch'])->name('blog.search');

    ///Brand section
    Route::resource('brand', \App\Http\Controllers\BrandController::class);
    Route::post('brand_status',[\App\Http\Controllers\BrandController::class,'brandStatus'])->name('brand.status');
    Route::get('brand_search',[\App\Http\Controllers\BrandController::class,'brandSearch'])->name('brand.search');
   
    ///Freetranstype section
    Route::resource('freetranstype', \App\Http\Controllers\FreetransTypeController::class);
    Route::post('freetranstype_status',[\App\Http\Controllers\FreetransTypeController::class,'freetranstypeStatus'])->name('freetranstype.status');
    Route::get('freetranstype_search',[\App\Http\Controllers\FreetransTypeController::class,'freetranstypeSearch'])->name('freetranstype.search');
    Route::get('freetrans_sort',[\App\Http\Controllers\FreeTransactionController::class,'freetransSort'])->name('freetransaction.sort');
   

    ///Product section
    Route::resource('product', \App\Http\Controllers\ProductController::class);
    Route::post('product_status',[\App\Http\Controllers\ProductController::class,'productStatus'])->name('product.status');
    Route::get('product_search',[\App\Http\Controllers\ProductController::class,'productSearch'])->name('product.search');
    Route::get('product_sort',[\App\Http\Controllers\ProductController::class,'productSort'])->name('product.sort');
    Route::get('product_jsearch',[\App\Http\Controllers\ProductController::class,'productJsearch'])->name('product.jsearch');
    Route::get('product_stock_quantity',[\App\Http\Controllers\ProductController::class,'productStock_quantity'])->name('product.stock_quantity');
    Route::get('product_jsearchwi',[\App\Http\Controllers\ProductController::class,'productJsearchwi'])->name('product.jsearchwi');
    Route::get('product_jsearchco',[\App\Http\Controllers\ProductController::class,'productJsearchco'])->name('product.jsearchco');
   
    
    Route::get('product_groupprice',[\App\Http\Controllers\ProductController::class,'productGPriceSearch'])->name('product.groupprice');
    Route::get('product_jsearchwo',[\App\Http\Controllers\ProductController::class,'productJsearchwo'])->name('product.jsearchwo');
    Route::post('product_add',[\App\Http\Controllers\ProductController::class,'productAdd'])->name('product.add');
    Route::get('product_jsearchwf',[\App\Http\Controllers\ProductController::class,'productJsearchwf'])->name('product.jsearchwf');
    Route::get('product_jsearchic',[\App\Http\Controllers\ProductController::class,'productJsearchic'])->name('product.jsearchic');
    Route::get('product_tsearch',[\App\Http\Controllers\ProductController::class,'productTsearch'])->name('product.tsearch');
    Route::get('product_msearch',[\App\Http\Controllers\ProductController::class,'productMsearch'])->name('product.msearch');
    Route::post('product_addm',[\App\Http\Controllers\ProductController::class,'productAddm'])->name('product.addm');
    Route::get('product_jsearchms',[\App\Http\Controllers\ProductController::class,'productJsearchms'])->name('product.jsearchms');
    Route::get('product_jsearchmtw',[\App\Http\Controllers\ProductController::class,'productJsearchmtw'])->name('product.jsearchmtw');
    Route::get('product_jsearchptw',[\App\Http\Controllers\ProductController::class,'productJsearchptw'])->name('product.jsearchptw');
    Route::get('product_price/{id}',[\App\Http\Controllers\ProductController::class,'productPriceView'])->name('product.priceview');
    Route::post('product_price',[\App\Http\Controllers\ProductController::class,'productPriceUpdate'])->name('product.priceupdate');
    Route::get('product_print',[\App\Http\Controllers\ProductController::class,'productPrint'])->name('product.print');
    Route::post('product_itcctv_jsearch',[\App\Http\Controllers\ProductController::class,'itcctv_jsearch'])->name('product.itcctv_jsearch');
    Route::get('product_itcctv_jsearch',[\App\Http\Controllers\ProductController::class,'itcctv_jsearch'])->name('product.itcctv_jsearch_get');
    Route::get('product_itcctv_detail',[\App\Http\Controllers\ProductController::class,'itcctv_productdetail'])->name('product.itcctv_productdetail');
    Route::get('product_productjmodsearch',[\App\Http\Controllers\ProductController::class,'productJmodsearch'])->name('product.productjmodsearch');
    

    
    Route::get('database_backup',[\App\Http\Controllers\BackupController::class,'backup'])->name('data.backup');
  
   
    
    //User section
    Route::resource('user', \App\Http\Controllers\UserController::class);
    Route::post('user_status',[\App\Http\Controllers\UserController::class,'userStatus'])->name('user.status');
    Route::get('user_search',[\App\Http\Controllers\UserController::class,'userSearch'])->name('user.search');
    Route::get('user_sort',[\App\Http\Controllers\UserController::class,'userSort'])->name('user.sort');
    Route::post('user_detail',[\App\Http\Controllers\UserController::class,'userDetail'])->name('user.detail');
    Route::post('user_profile',[\App\Http\Controllers\UserController::class,'userUpdateProfile'])->name('user.profileupdate');
    Route::get('user_profile',[\App\Http\Controllers\UserController::class,'userViewProfile'])->name('user.profileview');
    
    ///UGroup section
    Route::resource('ugroup', \App\Http\Controllers\UGroupController::class);
    Route::post('ugroup_status',[\App\Http\Controllers\UGroupController::class,'ugroupStatus'])->name('ugroup.status');
    Route::get('ugroup_search',[\App\Http\Controllers\UGroupController::class,'ugroupSearch'])->name('ugroup.search');

    ///Warehouse section
    Route::resource('warehouse', \App\Http\Controllers\WarehouseController::class);
    Route::post('warehouse_status',[\App\Http\Controllers\WarehouseController::class,'warehouseStatus'])->name('warehouse.status');
    Route::get('warehouse_search',[\App\Http\Controllers\WarehouseController::class,'warehouseSearch'])->name('warehouse.search');

    ///Log section
    Route::resource('log', \App\Http\Controllers\LogController::class);

    ///BeginInventory section
    Route::resource('binventory', \App\Http\Controllers\BInventoryController::class);
    Route::get('binventory_search',[\App\Http\Controllers\BInventoryController::class,'binventorySearch'])->name('binventory.search');
    Route::get('binventory_sort',[\App\Http\Controllers\BInventoryController::class,'binventorySort'])->name('binventory.sort');


    /// Inventory section
    Route::resource('inventory', \App\Http\Controllers\InventoryController::class);
    Route::get('inventory_search',[\App\Http\Controllers\InventoryController::class,'inventorySearch'])->name('inventory.search');
    Route::get('inventory_sort',[\App\Http\Controllers\InventoryController::class,'inventorySort'])->name('inventory.sort');
    Route::get('inventory_print',[\App\Http\Controllers\InventoryController::class,'inventoryPrint'])->name('inventory.print');
    Route::get('inventory_view/{id}',[\App\Http\Controllers\InventoryController::class,'inventoryView'])->name('inventory.view');
    Route::get('inventory_viewproduct/{id}',[\App\Http\Controllers\InventoryController::class,'inventoryViewProduct'])->name('inventory.viewproduct');

    /// Bankaccount section
    Route::resource('bankaccount', \App\Http\Controllers\BankController::class);
    Route::post('bankaccount_status',[\App\Http\Controllers\BankController::class,'bankaccountStatus'])->name('bankaccount.status');
    Route::get('banktrans_view',[\App\Http\Controllers\BankController::class,'banktransView'])->name('bankaccount.viewtrans');
    Route::get('banktrans_sort',[\App\Http\Controllers\BankController::class,'banktransSort'])->name('banktransaction.sort');
    Route::get('bankaccount_transfer/{id}',[\App\Http\Controllers\BankController::class,'bankaccountTransfer'])->name('bankaccount.transfer');
    Route::post('bankaccount_transfer_save',[\App\Http\Controllers\BankController::class,'bankaccountTransferSave'])->name('bankaccount.savetransfer');
    Route::get('banktrans_show/{id}',[\App\Http\Controllers\BankController::class,'banktransShow'])->name('banktrans.show');

    /// warehousein section
    Route::resource('warehousein', \App\Http\Controllers\WarehouseinController::class);
    Route::get('warehousein_search',[\App\Http\Controllers\WarehouseinController::class,'warehouseinSearch'])->name('warehousein.search');
    Route::get('warehousein_getProductList',[\App\Http\Controllers\WarehouseinController::class,'getProductList'])->name('warehousein.getProductList');
    Route::get('warehousein_paid/{id}',[\App\Http\Controllers\WarehouseinController::class,'warehouseinPaid'])->name('warehousein.paid');
    Route::post('warehousein_storepaid',[\App\Http\Controllers\WarehouseinController::class,'warehouseinSavePaid'])->name('warehousein.storepaid');
    Route::post('warehousein_return',[\App\Http\Controllers\WarehouseinController::class,'warehouseinReturn'])->name('warehousein.return');
    Route::get('warehousein_showold/{id}',[\App\Http\Controllers\WarehouseinController::class,'showold'])->name('warehousein.showold');
    
    Route::post('warehousein_add_einvoice',[\App\Http\Controllers\WarehouseinController::class,'add_einvoice'])->name('warehousein.add_einvoice');
   
    /// Supplier section
    Route::resource('supplier', \App\Http\Controllers\SupplierController::class);
    Route::get('supplier_search',[\App\Http\Controllers\SupplierController::class,'supplierSearch'])->name('supplier.search');
    Route::get('supplier_jsearch',[\App\Http\Controllers\SupplierController::class,'supplierJsearch'])->name('supplier.jsearch');
    Route::get('supplier_paid/{id}',[\App\Http\Controllers\SupplierController::class,'supplierPaid'])->name('supplier.paid');
    Route::post('supplier_storepaid',[\App\Http\Controllers\SupplierController::class,'supplierSavePaid'])->name('supplier.storepaid');
    Route::get('supplier_balance/{id}',[\App\Http\Controllers\SupplierController::class,'supplierMakeBalance'])->name('supplier.balance');
    Route::post('supplier_storereceived',[\App\Http\Controllers\SupplierController::class,'supplierSaveReceived'])->name('supplier.storereceived');
    Route::get('supplier_received/{id}',[\App\Http\Controllers\SupplierController::class,'supplierReceived'])->name('supplier.received');
    Route::get('moneyin/{id}',[\App\Http\Controllers\UserController::class,'moneyUserToStore'])->name('user.usertostore');
    Route::get('showsup/{id}',[\App\Http\Controllers\UserController::class,'moneyUsershow'])->name('user.showsup');
    Route::post('user_store_save',[\App\Http\Controllers\UserController::class,'moneySaveUserToStore'])->name('user.saveusertostore');
    Route::get('moneyout/{id}',[\App\Http\Controllers\UserController::class,'moneyStoreToUser'])->name('user.storetouser');
    Route::post('store_user_save',[\App\Http\Controllers\UserController::class,'moneySaveStoreToUser'])->name('user.savestoretouser');
  
    Route::get('supplier_sort',[\App\Http\Controllers\SupplierController::class,'supplierSort'])->name('supplier.sort');
    Route::post('supplier_status',[\App\Http\Controllers\SupplierController::class,'supplierStatus'])->name('supplier.status');
    Route::post('supplier_add',[\App\Http\Controllers\SupplierController::class,'supplierAdd'])->name('supplier.add');
    Route::get('supplier_productdetails/{id}',[\App\Http\Controllers\SupplierController::class,'BoughtProducts'])->name('supplier.productdetails');
    /// FreeTransaction section
    Route::resource('freetransaction', \App\Http\Controllers\FreeTransactionController::class);

    /// SupTransaction section
    Route::resource('suptransaction', \App\Http\Controllers\SupTransactionController::class);
    Route::get('suptrans_list',[\App\Http\Controllers\SupTransactionController::class,'suptransList'])->name('suptrans.list');
    Route::get('suptrans_sort',[\App\Http\Controllers\SupTransactionController::class,'suptransSort'])->name('suptrans.sort');
 
    /// Delivery section
    Route::resource('delivery', \App\Http\Controllers\DeliveryController::class);
    Route::get('delivery_search',[\App\Http\Controllers\DeliveryController::class,'deliverySearch'])->name('delivery.search');
    Route::get('delivery_jsearch',[\App\Http\Controllers\DeliveryController::class,'deliveryJsearch'])->name('delivery.jsearch');
    Route::get('delivery_sort',[\App\Http\Controllers\DeliveryController::class,'deliverySort'])->name('delivery.sort');
    Route::post('delivery_status',[\App\Http\Controllers\DeliveryController::class,'deliveryStatus'])->name('delivery.status');

    /// warehouseout section
    Route::resource('warehouseout', \App\Http\Controllers\WarehouseoutController::class);
    Route::get('warehouseout_search',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutSearch'])->name('warehouseout.search');
    Route::get('warehouseout_paid/{id}',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutPaid'])->name('warehouseout.paid');
    Route::post('warehouseout_storepaid',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutSavePaid'])->name('warehouseout.storepaid');
    Route::get('warehouseout_getProductList',[\App\Http\Controllers\WarehouseoutController::class,'getProductList'])->name('warehouseout.getProductList');
    Route::get('warehouseout_deprint/{id}',[\App\Http\Controllers\WarehouseoutController::class,'deliveryPrint'])->name('warehouseout.deprint');
    Route::post('warehouseout_return',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutReturn'])->name('warehouseout.return');
    Route::get('warehouseout_returnall',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutReturnall'])->name('warehouseout.returnall');
    Route::get('warehouseout_returndetail',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutReturndetail'])->name('warehouseout.returndetail');
    Route::post('warehouseout_savereturndetail',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutSaveReturndetail'])->name('warehouseout.savereturndetail');
    Route::post('warehouseout_updatereturndetail',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutUpdatereturndetail'])->name('warehouseout.updatereturndetail');
    Route::get('warehouseout_getProductListReturn',[\App\Http\Controllers\WarehouseoutController::class,'getProductListReturn'])->name('warehouseout.getProductListReturn');
    Route::post('warehouseout_warehouseoutDestroyReturndetail',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutDestroyReturndetail'])->name('warehouseout.warehouseoutDestroyReturndetail');
  
    
    
    Route::post('warehouseout_savereturnall',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutSaveReturnall'])->name('warehouseout.savereturnall');
    Route::post('warehouseout_returnnew',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutReturnNew'])->name('warehouseout.returnnew');
    Route::get('warehouseout_getOldProductList',[\App\Http\Controllers\WarehouseoutController::class,'getOldProductList'])->name('warehouseout.getOldProductList');
    Route::get('warehouseout_today',[\App\Http\Controllers\WarehouseoutController::class,'today'])->name('warehouseout.today');
    Route::post('warehouseout_publishitcctv',[\App\Http\Controllers\WarehouseoutController::class,'publishItcctv'])->name('warehouseout.publishitcctv');
   // ComboCreation section
   Route::resource('combo', \App\Http\Controllers\ComboController::class);
   Route::resource('combocreation', \App\Http\Controllers\ComboCreationController::class);
   Route::get('combo_search',[\App\Http\Controllers\ComboController::class,'comboSearch'])->name('combo.search');
   Route::post('combo_status',[\App\Http\Controllers\ComboController::class,'comboStatus'])->name('combo.status');
   Route::get('combo_getproductlist',[\App\Http\Controllers\ComboController::class,'getProductList'])->name('combo.getProductList');

   Route::get('combocreation_getproductlist',[\App\Http\Controllers\ComboCreationController::class,'getProductList'])->name('combocreation.getProductList');
   
   /////////////////////
    Route::get('warehouseout_showold/{id}',[\App\Http\Controllers\WarehouseoutController::class,'showold'])->name('warehouseout.showold');
    
    Route::get('warehouseout_new/{id}',[\App\Http\Controllers\WarehouseoutController::class,'warehouseoutNew'])->name('warehouseout.new');
    
    /// Customer section
    Route::resource('customer', \App\Http\Controllers\CustomerController::class);
    Route::get('customer_search',[\App\Http\Controllers\CustomerController::class,'customerSearch'])->name('customer.search');
    Route::get('customer_jsearch',[\App\Http\Controllers\CustomerController::class,'customerJsearch'])->name('customer.jsearch');
    Route::get('customer_paid/{id}',[\App\Http\Controllers\CustomerController::class,'customerPaid'])->name('customer.paid');
    Route::post('customer_storepaid',[\App\Http\Controllers\CustomerController::class,'customerSavePaid'])->name('customer.storepaid');
    Route::get('customer_balance/{id}',[\App\Http\Controllers\CustomerController::class,'customerMakeBalance'])->name('customer.balance');
    Route::post('customer_add',[\App\Http\Controllers\CustomerController::class,'customerAdd'])->name('customer.add');
    Route::get('customer_productdetails/{id}',[\App\Http\Controllers\CustomerController::class,'BoughtProducts'])->name('customer.productdetails');
    
    
    Route::get('customer_sort',[\App\Http\Controllers\CustomerController::class,'customerSort'])->name('customer.sort');
    Route::post('customer_status',[\App\Http\Controllers\CustomerController::class,'customerStatus'])->name('customer.status');
    Route::post('customer_storereceived',[\App\Http\Controllers\CustomerController::class,'customerSaveReceived'])->name('customer.storereceived');
    Route::get('customer_received/{id}',[\App\Http\Controllers\CustomerController::class,'customerReceived'])->name('customer.received');

    /// Setting  section
    Route::resource('setting', \App\Http\Controllers\SettingController::class);
    Route::get('setting_updatedata',[\App\Http\Controllers\SettingController::class,'viewUpdateData'])->name('setting.update_data');
    Route::post('setting_updateinvpro',[\App\Http\Controllers\SettingController::class,'updateInvPro'])->name('setting.updateinvpro');
    Route::post('setting_updatesitemap',[\App\Http\Controllers\SettingController::class,'updateSitemap'])->name('setting.updatesitemap');
    Route::post('setting_kiemtracongno',[\App\Http\Controllers\SettingController::class,'kiemtracongno'])->name('setting.kiemtracongno');
    Route::post('setting_cnsp_brand',[\App\Http\Controllers\SettingController::class,'nhap_san_pham_brand'])->name('setting.cnsp_brand');
    Route::post('setting_getbrand',[\App\Http\Controllers\SettingController::class,'view_brand'])->name('setting.getbrand');
    Route::post('setting_testapi',[\App\Http\Controllers\SettingController::class,'testApi'])->name('setting.testapi');
   
    
    
    /// order section
    Route::resource('order', \App\Http\Controllers\OrderController::class);
    Route::get('order_search',[\App\Http\Controllers\OrderController::class,'orderSearch'])->name('order.search');
    Route::get('order_getProductList',[\App\Http\Controllers\OrderController::class,'getProductList'])->name('order.getProductList');
    Route::get('order_out/{id}',[\App\Http\Controllers\OrderController::class,'orderOut'])->name('order.out');
    Route::post('order_outupdate',[\App\Http\Controllers\OrderController::class,'orderOutUpdate'])->name('order.outupdate');

     /// warehousetransfer section
    Route::resource('warehousetransfer', \App\Http\Controllers\WarehousetransferController::class);
    Route::get('warehousetrans_getProductList',[\App\Http\Controllers\WarehousetransferController::class,'getProductList'])->name('warehousetrans.getProductList');
    Route::get('warehousetrans_deprint/{id}',[\App\Http\Controllers\WarehousetransferController::class,'deliveryPrint'])->name('warehousetransfer.deprint');
  
     /// warehousetomaintain section
     Route::resource('warehousetomaintain', \App\Http\Controllers\WarehousetomaintainController::class);
    ///maitain
    Route::get('maintain_inv',[\App\Http\Controllers\InventoryMaintenanceController::class,'index'])->name('inventorymaintenance.index');
    Route::get('inventorym_view/{id}',[\App\Http\Controllers\InventoryMaintenanceController::class,'inventorymView'])->name('inventorymaintain.view');

    Route::get('maintain_search',[\App\Http\Controllers\InventoryMaintenanceController::class,'inventorySearch'])->name('inventorymaintenance.search');
    Route::get('maintain_sort',[\App\Http\Controllers\InventoryMaintenanceController::class,'inventorySort'])->name('inventorymaintenance.sort');
    Route::get('maintain_toshop',[\App\Http\Controllers\InventoryMaintenanceController::class,'inventoryToShop'])->name('inventorymaintenance.toshop');
    Route::get('maintain_toconsume',[\App\Http\Controllers\InventoryMaintenanceController::class,'inventoryToConsume'])->name('inventorymaintenance.toconsume');
    Route::get('maintain_todestroy',[\App\Http\Controllers\InventoryMaintenanceController::class,'inventoryToDestroy'])->name('inventorymaintenance.todestroy');
    Route::post('maintain_savetoshop',[\App\Http\Controllers\InventoryMaintenanceController::class,'inventorySaveToShop'])->name('inventorymaintenance.savetodestroy');

   
   
    ///maintainin
    Route::resource('maintainin', \App\Http\Controllers\MaintainInController::class);
    Route::post('maintainin_savereturn',[\App\Http\Controllers\MaintainInController::class,'maintaininSaveReturn'])->name('maintainin.savereturn');
    Route::post('maintainin_storepaid',[\App\Http\Controllers\MaintainInController::class,'maintaininSavePaid'])->name('maintainin.storepaid');
    Route::get('maintainin_paid/{id}',[\App\Http\Controllers\MaintainInController::class,'maintaininPaid'])->name('maintainin.paid');
    Route::get('maintainin_getitem',[\App\Http\Controllers\MaintainInController::class,'getItem'])->name('maintainin.getitem');
    Route::get('maintainin_viewfinish/{id}',[\App\Http\Controllers\MaintainInController::class,'maintaininViewFinish'])->name('maintainin.viewfinish');
   
    Route::post('maintainin_savefinish',[\App\Http\Controllers\MaintainInController::class,'maintaininSaveFinish'])->name('maintainin.savefinish');
    Route::get('maintainin_editpaid/{id}',[\App\Http\Controllers\MaintainInController::class,'edit_paid_amount'])->name('maintainin.edit_paid');
    Route::post('maintainin.update_paid',[\App\Http\Controllers\MaintainInController::class,'maintaininUpdatepaid'])->name('maintainin.updatepaid');
    
    ///maintainsent
    Route::resource('maintainsent', \App\Http\Controllers\MaintainSentController::class);
    Route::get('maintainsent_getProductList',[\App\Http\Controllers\MaintainSentController::class,'getProductList'])->name('maintainsent.getProductList');
    Route::get('maintainsent_deprint/{id}',[\App\Http\Controllers\MaintainSentController::class,'deliveryPrint'])->name('maintainsent.deprint');
  
     ///maintainsent
     Route::resource('maintainback', \App\Http\Controllers\MaintainBackController::class);
     Route::get('maintainback_getProductList',[\App\Http\Controllers\MaintainBackController::class,'getProductList'])->name('maintainback.getProductList');
     Route::get('maintainback_deprint/{id}',[\App\Http\Controllers\MaintainBackController::class,'deliveryPrint'])->name('maintainback.deprint');
     Route::get('maintainback_paid/{id}',[\App\Http\Controllers\MaintainBackController::class,'maintainbackPaid'])->name('maintainback.paid');
     Route::post('maintainback_storepaid',[\App\Http\Controllers\MaintainBackController::class,'maintainbackSavePaid'])->name('maintainback.storepaid');
    //bbanktran
    Route::resource('bbanktrans', \App\Http\Controllers\BBanktransController::class);
    ///maintaintowarehouse
    Route::resource('maintaintowarehouse', \App\Http\Controllers\MaintaintoWarehouseController::class);
    ///maintaintodestroy
    Route::resource('maintaintodestroy', \App\Http\Controllers\MaintaintoDestroyController::class);
    ///inventorydestroy
    Route::resource('inventorydestroy', \App\Http\Controllers\InventoryDestroyController::class);
    Route::get('inventorydestroy_search',[\App\Http\Controllers\InventoryDestroyController::class,'inventorySearch'])->name('inventorydestroy.search');
    Route::get('inventorydestroy_sort',[\App\Http\Controllers\InventoryDestroyController::class,'inventorySort'])->name('inventorydestroy.sort');
    Route::get('inventoryd_view/{id}',[\App\Http\Controllers\InventoryDestroyController::class,'inventorydView'])->name('inventorydestroy.view');

    ///maintaintodestroy
    Route::resource('maintaintoproperty', \App\Http\Controllers\MaintaintoPropertyController::class);
    ///inventorydproperty
    Route::resource('inventoryproperty', \App\Http\Controllers\InventoryPropertyController::class);
    Route::get('inventoryproperty_search',[\App\Http\Controllers\InventoryPropertyController::class,'inventorySearch'])->name('inventoryproperty.search');
    Route::get('inventoryproperty_sort',[\App\Http\Controllers\InventoryPropertyController::class,'inventorySort'])->name('inventoryproperty.sort');
    Route::get('inventoryp_view/{id}',[\App\Http\Controllers\InventoryPropertyController::class,'inventorypView'])->name('inventoryproperty.view');

     ///warehousetoproperty
     Route::resource('warehousetoproperty', \App\Http\Controllers\WarehousetoPropertyController::class);
    ///warehousetodestroy
    Route::resource('warehousetodestroy', \App\Http\Controllers\WarehousetoDestroyController::class);
    ///propertytowarehouse
    Route::resource('propertytowarehouse', \App\Http\Controllers\PropertytoWarehouseController::class);
    ///propertytodestroy
    Route::resource('propertytodestroy', \App\Http\Controllers\PropertytoDestroyController::class);
   ///propertytomaintain
   Route::resource('propertytomaintain', \App\Http\Controllers\PropertytoMaintainController::class);
   ///inventorycheck
   Route::resource('inventorycheck', \App\Http\Controllers\InventoryCheckController::class);
  
   ///inventorycheck
   Route::resource('modpro', \App\Http\Controllers\FrontModProController::class);
   Route::post('modpro_status',[\App\Http\Controllers\FrontModProController::class,'modproStatus'])->name('modpro.status');
   Route::get('modpro_addpro/{id}',[\App\Http\Controllers\FrontModProController::class,'modproAddpro'])->name('modpro.addpro');
   Route::post('modpro_savepro',[\App\Http\Controllers\FrontModProController::class,'modproSavepro'])->name('modpro.savepro');
   Route::post('modpro_removepro',[\App\Http\Controllers\FrontModProController::class,'modproRemovepro'])->name('modpro.removepro');
   Route::get('cproduct_price/{id}/{mod_id}',[\App\Http\Controllers\FrontModProController::class,'productPriceView'])->name('cproduct.priceview');
   Route::post('cproduct_price',[\App\Http\Controllers\FrontModProController::class,'productPriceUpdate'])->name('cproduct.priceupdate');
   Route::get('modpro_up/{id}/{mod_id}',[\App\Http\Controllers\FrontModProController::class,'up'])->name('modpro.up');
   Route::get('modpro_down/{id}/{mod_id}',[\App\Http\Controllers\FrontModProController::class,'down'])->name('modpro.down');
   Route::get('inventorycheck_getProductList',[\App\Http\Controllers\InventoryCheckController::class,'getProductList'])->name('inventorycheck.getProductList');
   Route::get('admin_getoutmonth', [\App\Http\Controllers\AdminController::class,'out_month_view'])->name('admin.getoutmonth');
   Route::get('admin_getoutday', [\App\Http\Controllers\AdminController::class,'out_day_view'])->name('admin.getoutday');
   Route::get('admin_getinmonth', [\App\Http\Controllers\AdminController::class,'in_month_view'])->name('admin.getinmonth');
   Route::get('admin_getinday', [\App\Http\Controllers\AdminController::class,'in_day_view'])->name('admin.getinday');
   Route::get('admin_getoutyear', [\App\Http\Controllers\AdminController::class,'out_year_view'])->name('admin.getoutyear');
   Route::get('admin_getinyear', [\App\Http\Controllers\AdminController::class,'in_year_view'])->name('admin.getinyear');
   Route::get('admin_getoutall', [\App\Http\Controllers\AdminController::class,'out_all_view'])->name('admin.getoutall');
   Route::get('admin_getinall', [\App\Http\Controllers\AdminController::class,'in_all_view'])->name('admin.getinall');
  ////report
  
  Route::resource('comment',\App\Http\Controllers\CommentController::class);
  Route::post('comment_status',[\App\Http\Controllers\CommentController::class,'commentStatus'])->name('comment.status');
  Route::get('comment_search',[\App\Http\Controllers\CommentController::class,'commentSearch'])->name('comment.search');
  
    Route::get('report_chitietcongno/{id}', [\App\Http\Controllers\ReportController::class,'reportCongnoChitiet'])->name('report.chitietcongno');
    
    Route::get('report_money', [\App\Http\Controllers\ReportController::class,'reportBenefit'])->name('report.money');
    Route::get('report_thuchi', [\App\Http\Controllers\ReportController::class,'reportThuchi'])->name('report.thuchi');
    Route::get('report_congnokhach', [\App\Http\Controllers\ReportController::class,'reportCongnokhach'])->name('report.congnokhach');
    Route::get('report_congnosup', [\App\Http\Controllers\ReportController::class,'reportCongnosup'])->name('report.congnosup');
    Route::get('report_sanpham', [\App\Http\Controllers\ReportController::class,'reportSanpham'])->name('report.sanpham');
    Route::get('report_quy', [\App\Http\Controllers\ReportController::class,'reportQuy'])->name('report.quy');
  ///////kiotreportCongnosup
    Route::get('kiot_index', [\App\Http\Controllers\SettingController::class,'KiotIndex'])->name('kiot.index');
    Route::post('kiot_categoryupdate', [\App\Http\Controllers\SettingController::class,'KiotCategoryUpdate'])->name('kiot.categoryupdate');
    Route::post('kiot_productupdate', [\App\Http\Controllers\SettingController::class,'KiotProductUpdate'])->name('kiot.productupdate');
    Route::post('kiot_customerupdate', [\App\Http\Controllers\SettingController::class,'KiotCustomerUpdate'])->name('kiot.customerupdate');
    Route::post('kiot_brancheupdate', [\App\Http\Controllers\SettingController::class,'KiotBranchUpdate'])->name('kiot.brancheupdate');
    Route::post('kiot_userupdate', [\App\Http\Controllers\SettingController::class,'KiotuserUpdate'])->name('kiot.userupdate');
    Route::post('kiot_bankaccountupdate', [\App\Http\Controllers\SettingController::class,'KiotBankUpdate'])->name('kiot.bankaccountupdate');
    Route::post('kiot_customergroupupdate', [\App\Http\Controllers\SettingController::class,'KiotCustomerGroupUpdate'])->name('kiot.customergroupupdate');
    Route::post('kiot_warehouseinupdate', [\App\Http\Controllers\SettingController::class,'KiotWarehouseinupdateUpdate'])->name('kiot.warehouseinupdate');
    Route::post('kiot_flowupdate', [\App\Http\Controllers\SettingController::class,'KiotFlowUpdate'])->name('kiot.flowupdate');
    Route::post('kiot_warehouseoutupdate', [\App\Http\Controllers\SettingController::class,'KiotWarehouseoutupdateUpdate'])->name('kiot.warehouseoutupdate');
    Route::post('kiot_updatebenefit', [\App\Http\Controllers\SettingController::class,'updateBenefit'])->name('kiot.updatebenefit');
   /////file upload/////////
 
    Route::post('avatar-upload', [\App\Http\Controllers\FilesController::class, 'avartarUpload' ])->name('upload.avatar');
    
    Route::post('product-upload', [\App\Http\Controllers\FilesController::class, 'productUpload' ])->name('upload.product');
    Route::post('upload-ckeditor', [\App\Http\Controllers\FilesController::class, 'ckeditorUpload' ])->name('upload.ckeditor');

    
});

});
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']],
 function () { \UniSharp\LaravelFilemanager\Lfm::routes();});
///them cho glide
 Route::get('glide/{path}', function($path){
    $server = \League\Glide\ServerFactory::create([
        'source' => app('filesystem')->disk('gcs')->getDriver(),
    'cache' => storage_path('glide'),
    ]);
    return $server->getImageResponse($path, Input::query());
})->where('path', '.+');
////end//////////////

Route::get('unauthorized',[\App\Http\Controllers\Controller::class,'unauthorized'])->name('unauthorized');
