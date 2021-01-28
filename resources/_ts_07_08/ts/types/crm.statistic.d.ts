import {ICustomer} from "./customer";
import {IAct} from "./act";
import {IVolume} from "./volume";
import {ILid} from "./lid";

export interface IPeriod {
    start: string,
    end: string,
    type?: string
}

export interface ISettings {
    modal: boolean,
    tabs: {
        site: string,
        manager: string,
        status: string,
        conversion: string
    },
    activeTab: string
}

export interface ICrmStatistic {
    id?: number ,
    lid_id?: number | null,
    act_id?: number | null,
    customer_id?: number | null,
    date_start?: any | null,
    cost_date?: any | null,
    nr?: number | null,
    date?: any | null,
    time?: any | null,
    site?: number | null,
    manager?: number,
    call_status?: number,
    app_status?: number,
    cause?: string | null,
    address?: string | null,
    cash?: number | null,
    cashless?: number | null,
    cash_tag?: number,
    cashless_tag?: number,
    phone?: string | null,
    phone_ext?: string | null,
    phone_sub?: string | null,
    phone_ext_sub?: string | null,
    name?: string | null,
    customer_status?: number,
    contract?: string | null,
    offer_sent?: number,
    implement_date?: string | null,
    customer?: ICustomer | null,
    refusal?: string | null,
    col?: string | null,
    editing?: number | null,
    lid?: ILid | null,
    act?: IAct | null,
    volumes?: IVolume[] | null,
    lat ? : string,
    lon ? : string,
    destination ? : string,
    region ? : string,
    card_payment ? : number,
    overpay ? : number,
    contractor_fee ? : number,
    pay_back ? : number,
    property_type ? : number,
    updated_at?: string | null,
    created_at?: string | null,

    lid_customer_payment ? : number,
    lid_payment_rule ? : number,
    lid_payment_condition ? : number,
    lid_payment_system ? : number,
    lid_price_total ? : number,
    lid_price_cash ? : number,
    lid_price_cashless ? : number,
    lid_rules_prepayment? : number,
    lid_rules_postpayment? : number,
}


export interface IStatisticTable {
    [index: number]: ICrmStatistic
}
export interface ICallStatus {
    id: number,
    name: string,
    select: boolean,
    disabled?: boolean,
    class?: string,
    design?: string,
}
export interface IAppStatus {
    id: number,
    name: string,
    design?: string,
}
export interface ICardPayment {
    value: number,
    name: string
}
