export interface Window {
    Echo: any;
}

export interface IHelper {
    actions?: object,
    companies?: ICompanies,
    customer?: object,
    drugs?: Array<any>,
    equipments?: object,
    methods?: IMethods[],
    methods_asc?: IMethods[],
    payment_conditions?: object,
    payment_rules?: object,
    pests: IPests,
    pests_asc: IPestsAsc[],
    phones?: object,
    regions?: object,
    roles?: object,
    roles_obj?: object,
    rules?: object,
    servicing?: object,
    square?: object,
    statuses?: object,
    tools?: Array<any>,
    user: IUser,
    users:{[index: number] : IHelperUsers}
}

export interface IHelperUsers {
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

export interface IPests {
    [index: number]: {
        id: number,
        sort: number,
        active: number,
        name: string,
        methods: [string],
        created_at: string,
        updated_at: string
    }
}

export interface IPestsAsc {
    id: number,
    sort: number,
    active: number,
    name: string,
    methods: Array<number>,
    created_at: string,
    updated_at: string
}

/*export interface IMethods {
    [index: number]: IMethod
}*/
export interface IMethods {
    id?: number,
    sort: number,
    name: string,
    cost: number,
    unit: string,
    created_at: string,
    updated_at: string
}

export interface IIdName {
    id: number,
    name: string
    short?: string
}

export interface IFieldValue {
    id?: number,
    field: string,
    value: any
}

export interface IKeyValue {
    [key: string]: any
}

export interface IUser {
    active: number,
    avatar: string,
    city: number,
    color: string,
    id: number,
    name: string,
    percent: number,
    percent_limit: number,
    phone: number | string,
    role_id: number,
    salary: number,
    shift: number,
    text_color: string,
    timeline: number,
    username: string,
}
