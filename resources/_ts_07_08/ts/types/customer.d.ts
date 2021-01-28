export interface ICustomer {
    id?: number,
    name? :  string,
    icon? :  string,
    status? :  number,
    organization? :  string,
    organization_long? :  string,
    phone? :  string,
    phone_ext? :  string,
    phone_sub? :  string,
    phone_ext_sub? :  string,
    address? :  string,
    addresses? :  string,
    address_source? : string,
    email? :  string,
    emails? :  string,
    destination? :  string,
    city? :  string,
    region? :  string,
    lat? :  string,
    lon? :  string,
    inn? :  string,
    kpp? :  string,
    ogrn? :  string,
    zip? : string,
    management_name? :  string,
    management_post? :  string,
    bank_payment? :  string,
    bank_ca? :  string,
    bank_bic? :  string,
    bank_account? :  string
}

export interface ICustomersLids {
    field: ILid,
    value: any
}
