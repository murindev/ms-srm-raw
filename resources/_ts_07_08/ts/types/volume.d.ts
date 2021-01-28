export interface IVolume {
    id?: number,
    parent?: number,
    lid_id?: number,
    statistic_id?: number,
    pest?: number,
    square?: number,
    square_fact?: number,
    entity?: number,
    method?: string | null,
    price_standard?: number,
    price_fact?: number,
    kpi?: number,
    note?: string,
    created_at?: string,
    updated_at?: string,
}

export interface IVolumeDelete {
    id: number,
    crm_statistic_id: number
}
