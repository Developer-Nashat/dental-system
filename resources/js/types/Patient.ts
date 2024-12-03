export interface Patient {
    id: number;
    name: string;
    age?: number;
    gender?: "M" | "F";
    identification_no?: string;
    address?: string;
    phone_1?: string;
    phone_2?: string;
    note?: string;
    account_id?: string;
}
