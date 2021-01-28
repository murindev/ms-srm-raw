import {IVolume} from "./volume";
import {IImplement} from "./implement";

export interface IAct {
    id?: number,
    parent?: number | null,                    // лид
    act_nr?: string,
    statistic_id?: number,
    creator?: number,                   // Создатель
    subact?: number,                    // ПОД Акт
    contact?: string,                   // Контактное лицо - !!!!!
    phone?: string,
    phone_sub?: string,
    phone_ext_sub?: string,
    phone_ext?: string,
    floating?: number,                  // Плавающий?
    floating_date_from?: string,        // Плавающий с
    floating_date_to?: string,          // Плавающий до
    prefer_time_from?: string,
    prefer_time_to?: string,
    finished?: number,// Звершен
    implement_first? : IImplement,  // date from implement
    booking_act_signed?: number,        //	Бух. акт подписан?
    booking_act_transferred?: number,   // Бух. акт передан?
    booking_act_file?: string,          //	Бух. акт скан
    implement_act_signed?: number,     // Исп. акт подписан?
    implement_act_file?: string,       // Исп. акт скан
    address?: string,   //
    lat?: string,   //
    lon?: string,   //
    destination?: string,   //
    region?: string,   //
    cash?: number,   //
    cashless?: number,   //
    cash_tag?: number,   //
    cashless_tag?: number,   //
    contact_name?: string,              // Контактное лицо
    comment?: string,   //
    cost_remedy?: number,   //
    cost_transport?: number,   //
    cost_labor?: number,   //
    card_payment?: number,   //
    overpay?: number,   //
    contractor_fee?: number,   //
    contractor_fee_cashless?: number,   //
    pay_back?: number,   //
    property_type?: number,   //
    volumes?: IVolume[],   //
    volume?: IVolume[],   //
    implements?: IImplement[] | null,
    created_at?: string,   //
    updated_at?: string,   //
}

export interface IPropertyType {
    id: number,
    name: string
}
export interface IPaid {
    id: number,
    name: string
}
