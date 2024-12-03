export interface Visit {
    id?: string;
    patient_id: number;
    patient_name: string;
    dentist_id: string;
    dentist_name: string;
    status_id: number;
    status: string;
    visit_date: Date;
    visit_time: Date;
    is_done: boolean;
}
