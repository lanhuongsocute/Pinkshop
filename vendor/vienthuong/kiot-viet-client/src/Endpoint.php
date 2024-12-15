<?php declare(strict_types=1);

namespace VienThuong\KiotVietClient;

class Endpoint
{
    const AUTH_ENDPOINT = 'https://id.kiotviet.vn/connect/token';

    const CUSTOMER_ENDPOINT = 'https://public.kiotapi.com/customers';
    const CUSTOMER_LIST_ADD_ENDPOINT = 'https://public.kiotapi.com/listaddcustomers';

    /**
     * Nhóm khách hàng
     */
    const CUSTOMER_GROUP_ENDPOINT = 'https://public.kiotapi.com/customers/group';

    const PRODUCT_ENDPOINT = 'https://public.kiotapi.com/products';
    const PRODUCT_ATTRIBUTES_ENDPOINT = 'https://public.kiotapi.com/attributes/allwithdistinctvalue';
    const PRODUCT_LIST_ADD_ENDPOINT = 'https://public.kiotapi.com/listaddproducts';

    const CATEGORY_ENDPOINT = 'https://public.kiotapi.com/categories';
    const INVOICE_ENDPOINT = 'https://public.kiotapi.com/invoices';
    const ORDER_ENDPOINT = 'https://public.kiotapi.com/orders';
    const BRANCH_ENDPOINT = 'https://public.kiotapi.com/branches';

    const SURCHARGE_ENDPOINT = 'https://public.kiotapi.com/surchages';

    const USER_ENDPOINT = 'https://public.kiotapi.com/users';

    const WEBHOOK_ENDPOINT = 'https://public.kiotapi.com/webhook';

    /**
     * Sổ quỹ
     */
    const CASH_FLOW_ENDPOINT = 'https://public.kiotapi.com/cashflow';

    /**
     * Nhập hàng
     */
    const PURCHASE_ORDER_ENDPOINT = 'https://public.kiotapi.com/purchaseorders';

    /**
     * Bảng giá
     */
    const PRICE_BOOK_ENDPOINT = 'https://public.kiotapi.com/pricebooks';

    /**
     * Kênh bán hàng
     */
    const SALE_CHANNEL_ENDPOINT = 'https://public.kiotapi.com/salechannel';

    /**
     * Trả hàng
     */
    const RETURN_ENDPOINT = 'https://public.kiotapi.com/returns';

    /**
     * Tài khoản ngân hàng
     */
    const BANK_ACCOUNT_ENDPOINT = 'https://public.kiotapi.com/BankAccounts';

    /**
     * Địa chỉ thành phố phường xã
     */
    const LOCATION_ENDPOINT = 'https://public.kiotapi.com/locations';
}
