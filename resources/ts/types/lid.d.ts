import {IAct} from "./act";
import {IFinance} from "./finance";
import {ICustomer} from "./customer";

export interface ILid {
    id?: number,
    manager?: number,
    site?: number,
    manager_starter?: number,
    customer?: number ,
    client?: ICustomer ,
    contract?: string,
    date_from?: string,
    date_to?: string,
    contract_transferred?: number,
    contract_signed?: number,
    contract_file?: string,
    servicing?: number,
    action?: number,
    date_start?: string,
    action_date?: string,
    action_note?: string,
    customer_status?: number,

    comment?: string

    kpi?: number,

    customer_payment?: number,
    payment_system?: number,

    payment_rule?: number,
    rules_prepayment?: number,
    rules_postpayment?: number,
    rules_prepayment_rest?: number,
    rules_postpayment_rest?: number,

    payment_condition?: number,

    price_total?: number,
    price_cash?: number,
    price_cashless?: number,
    receipts_total?: number,
    receipts_cash?: number,
    receipts_cashless?: number,
    exp_total?: number,
    exp_cash?: number,
    exp_cashless?: number,

    volumes_total?: number,
    volumes_cash?: number,
    volumes_cashless?: number,


    acts?: IAct[],
    finances?: IFinance[]

    created_at?: string,
    updated_at?: string,



    balance ? : number | null,
    payoff_total ? : number | null,
    payoff_cash ? : number | null,
    payoff_cashless ? : number | null,
    payed_off_total ? : number | null,
    payed_off_cash ? : number | null,
    payed_off_cashless ? : number | null,
    to_payoff_total ? : number | null,
    to_payoff_cash ? : number | null,
    to_payoff_cashless ? : number | null



}

