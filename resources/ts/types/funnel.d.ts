import {IAppStatus, ICallStatus} from "./crm.statistic";
import {IIdName} from "./Global";
import {IFinance} from "./finance";


export interface IFunnelData {
    byLid: {
        actPropertyTypes: {},
        actVolumeFinishedPrices: {},
        actVolumeMethods: {},
        actVolumePest: {},
        actVolumePrices: {},
        actVolumeSquareEntities: {},
        lidCustomerStatus: {},
        lidOrderSource: {},
        lidPaymentSystem: {},
        lidServicing: {},
    },
    byStat: {
        appStatuses: {
            byManagers: {
                [managerId: string]: {
                    [statusId: string]: number
                }
            },
            byManagersOrdered: {
                [managerId: string]: {
                    [statusId: string]: number
                }
            },
            common: {
                [key: string]: number
            }
            commonOrdered: {
                [key: string]: number
            }
        },
        callStatuses: {
            byManagers: {
                [managerId: string]: {
                    [statusId: string]: number
                }
            },
            common: {
                [key: string]: number
            }
        },
        customer: {
            byManagers: {
                [managerId: string]: {
                    [statusId: string]: number
                }
            },
            byManagersOrdered: {
                [managerId: string]: {
                    [statusId: string]: number
                }
            },
            common: {
                [key: string]: number
            }
            commonOrdered: {
                [key: string]: number
            }
        },
        sources: {
            byManagers: {
                [managerId: string]: {
                    [statusId: string]: number
                }
            },
            common: {
                [key: string]: number
            }
        },
    },

}


export interface IFunnel {
    stat: {
        callStatuses: {
            common: ICallStatus[],
            byManagers: {
                [key: number]: ICallStatus[]
            }
        },
        appStatuses: {
            common: IAppStatus[],
            byManagers: {
                [key: number]: IAppStatus[]
            }
        },
        customer: {
            common: IIdName[],
            byManagers: {
                [key: number]: IIdName[]
            }
        },
        sources: {
            common: {},
            byManagers: {}
        }
    },
    lid: {
        errors: {
            emptyContracts: number[],
            managerStarter: number[],
            currentManager: number[],
            emptyServicing: number[],
            emptyCustomer: number[],
            finances: {
                actNotExist: IFinance[]
            }
        },
        common: Object<IFunnelByLid>,
        byManagers: {
            [key: number]: IFunnelByLid
        }
    }
}


export interface IFunnelByLid {
    lidOrderSource: { // Сайт  \\ за период
        [key: string]: number
    },
    lidServicing: { // Тип обслуживания:
        [key: string]: number
    }
    lidCustomer: {
        [key: string]: number
    },
    lidPaymentSystem: { //Система оплаты:
        [key: string]: number
    },
    lidCustomerPayment: {//Форма оплаты:
        [key: string]: number
    },
    lidPaymentRule: { // payment_rule
        [key: string]: number
    },
    lidContractTransferred: { // contract_transferred
        [key: string]: number
    },
    lidContractSigned: { // contract_signed
        [key: string]: number
    },
    lidContractFile: { // contract_file
        [key: string]: number
    },

    // ACTS
    actPropertyTypes: { // Тип обьекта property_type
        [key: string]: number
    },


    /*
    * За период
    * - новые лиды
    * - новые акты
    *
    *
    * */

    /*
    * За период на сумму
    * - новые акты (+ количество)
    * - поступления
    * - оплаченные акты (из фин таблицы)
    *
    *
    * */


    /*
    actOverpay: { //overpay
        // Накрутка мастером Мастер+ (вся сумма)
    },
    overpay_rest: { // overpay_rest
       // Мастер+ (остаток)
        Субчику contractor_fee
        Субчику нал contractor_fee_cash
        Субчику безнал contractor_fee_cashless
        Отказ pay_back
        Отказ нал pay_back_cash
        Отказ безнал pay_back_cashless
    }
     */

    // VOLUMES

    actVolumePest: { // pest
        [key: string]: number
    },
    actVolumeSquareEntities: { // entity
        [key: string]: number
    },
    actVolumeMethods?: { //method
        [key: string]: number
    },


    // FINANCES by Volumes
    actVolumePrices: {
        cashSum: number,
        cashlessSum: number
    },

    actVolumeFinishedPrices: {
        cashSum: number,
        cashlessSum: number
    },

    actVolumePayoffs: {
        cashSum: number,
        cashlessSum: number,
    },
    // FINANCES by finances
    finReceipts: {
        cash: number,
        cashless: number,
    },
    finPayoffs: {
        cash: number,
        cashless: number,
    },

}


export interface IFunnelFinances {

    byFinActivity: {
        newImplemented: {
            [key: string]: {
                cash: number
                cashless: number
            }
        },
        newTotal: {
            [key: string]: {
                cash: number
                cashless: number
            }
        }
    },

    byFinImplement: {
        byImplementManagers: {
            [key: string]: {
                cash: number,
                cashless: number,
                finishedCash: number,
                finishedCashless: number,
            }
        },
        byImplementTotal: {
            cash: number,
            cashless: number,
            finishedCash: number,
            finishedCashless: number,
        }
    },

    byFinTotal: {
        byFinManagers: {
            [key: string]: {
                creditCash: number,
                creditCashless: number,
                debitCash: number,
                debitCashless: number,
            }
        },
        byFinTotal: {
            creditCash: number,
            creditCashless: number,
            debitCash: number,
            debitCashless: number,
        }
    },

}
