export const items = [
    {
        id: 1,
        title: "الإدارة",
        link: null,
        file: null,
        children: [
            {
                id: 2,
                title: "المدخلات الاساسية",
                link: null,
                file: null,
                children: [
                    {
                        id: 3,
                        title: "المشاكل",
                        link: "problemCatalogs",
                        file: "ProblemIcon",
                    },
                    {
                        id: 4,
                        title: "العلاجات",
                        link: "treatments",
                        file: "TreatmentIcon",
                    },

                    {
                        id: 5,
                        title: "المرضى",
                        link: "patients",
                        file: "PatientIcon",
                    },
                ],
            },

            {
                id: 6,
                title: "الإجراءات اليومية",
                link: null,
                file: null,
                children: [
                    {
                        id: 7,
                        title: "الزيارات",
                        link: "visits",
                        file: "VisitIcon",
                    },
                ],
            },
        ],
    },
];
