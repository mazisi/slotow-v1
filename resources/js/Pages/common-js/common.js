

const PROVINCES =      ['Eastern Cape',
                        'Free State',
                        'Gauteng',
                        'KwaZulu-Natal',
                        'Limpopo',
                        'Mpumalanga',
                        'Northern Cape',
                        'North West',
                        'Western Cape'
                        ];

const BOARD_REGIONS  = ['Eastern Cape',
                        'Free State',
                        'Gauteng - Ekurhuleni',
                        'Gauteng - Johannesburg',
                        'Gauteng - Sedibeng',
                        'Gauteng - Tshwane',
                        'Gauteng - West Rand',
                        'KwaZulu-Natal',
                        'Limpopo',
                        'Mpumalanga',
                        'National Liquor Authority',
                        'Northern Cape',
                        'North West',
                        'Western Cape',
                      ];

  const COMPANY_TYPES = [
                      "Association",
                      "Close Corporation CC",
                      "Individual",
                      "Non-profit Organization (NPO)",
                      "Partnership",
                      "Private Company  (Proprietary) Limited",
                      "Public Company",
                      "Sole Proprietor",
                      "Trust"                  
                    ]

export default {
  getProvinces() {
    return PROVINCES;
  },

  getCompanyTypes() {
    return COMPANY_TYPES;
  },

  getBoardRegions() {
    return BOARD_REGIONS;
  },
  
}
