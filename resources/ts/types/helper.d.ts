export interface IHelper {
    actions?: object,
    companies?: ICompanies,
    customer?: IHelperCustomerType,
    drugs?: Array<any>,
    equipments?: object,
    methods?: object,
    payment_conditions?: object,
    payment_rules?: object,
    pests?: object,
    phones?: object,
    regions?: IHelperRegion[],
    roles?: object,
    roles_obj?: object,
    rules?: object,
    servicing?: object,
    square?: object,
    statuses?: object,
    tools?: Array<any>,
    user?: object,
    users?: IHelperUsers
}

export interface IArrHelper {
    actions?: object,
    companies?: ICompanies,
    customer?: IHelperCustomerType,
    drugs?: Array<any>,
    equipments?: object,
    methods?: object,
    payment_conditions?: object,
    payment_rules?: object,
    pests?: object,
    phones?: object,
    regions?: IHelperRegion[],
    roles?: object,
    roles_obj?: object,
    rules?: object,
    servicing?: object,
    square?: object,
    statuses?: object,
    tools?: Array<any>,
    user?: object,
    users: IHelperUsers[]
}

export interface IHelperRegion {
    // [index: number] : {
        id? :  number,
        region? :  string,
        area? :  string,
        city? :  string,
        code? :  number,
        phone? :  string,
        center_lat? :  string,
        center_lon? :  string,
        zoom? :  number,
        created_at? :  string,
        updated_at? :  string
    // }

}

export interface IHelperCustomerType {
    [index: number]: {
        id? :  number,
        name? :  string,
        value? :  number,
        created_at? :  string,
        updated_at? :  string
    }
}

export interface IHelperUsers {
    id: number,
    active?: number,
    avatar?: string,
    city?: number,
    color?: string,
    text_color?: string,
    name?: string,
    role_id?: number,
    salary?: number,
    timeline?: number,
    shift?: number,
    key?: any
}

export interface ICompanies {
    [index: number]: {
        id: number,
        name: string,
        email: string,
        created_at: string,
        updated_at: string
    }
}
