export interface IFinance {
    id?: number,
    amount?: number,
    form?: number,
    type?: number,
    lid_id?: number,
    act_id?: number,
    contract?: string,
    customer?: number,
    manager?: number,
    created_at?: string,
    updated_at?: string,
}

export interface IPaymentForm {
    id?: number,
    name?: string,
    short?: string,
    shortest?: string
}

export interface IPaymentType {
    id?: number,
    name?: string,
    short?: string,
    shortest?: string
}
