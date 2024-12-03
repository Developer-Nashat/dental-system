export interface Header {
    title?: string;
    align?: "start" | "end" | "center" | undefined;
    key?: string;
    sortable?: boolean;
    width?: string | undefined;
    minWidth?: string | undefined;
    maxWidth?: string | undefined;
}
