<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\department;
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        \DB::table('departments')->truncate();
        $departments_raw = $this->filler();

        foreach ($departments_raw as $key => $department) {
            department::create($department);
        }
       
        
        
    }


    public function filler(){
        return array(
            array(
                "id" => 1,
                "code" => "1041A",
                "payroll_code" => "1041",
                "description" => "PPDO-Administrative Division",
                "department_initial" => "PPDO-Admin",
                "payroll_title" => "Planning and Development Services",
                "department_head" => 22,
                "payroll_officer" => 307,
                "manhour_sign" => 22
            ),
            array(
                "id" => 2,
                "code" => "1041B",
                "payroll_code" => "1041",
                "description" => "PPDO-Planning & Programming Division",
                "department_initial" => "PPDO-Planning",
                "payroll_title" => "Planning and Development Services",
                "department_head" => 22,
                "payroll_officer" => 307,
                "manhour_sign" => 22
            ),
            array(
                "id" => 3,
                "code" => "1041C",
                "payroll_code" => "1041",
                "description" => "PPDO-Statistics & Evaluation Division",
                "department_initial" => "PPDO-Statistics",
                "payroll_title" => "Planning and Development Services",
                "department_head" => 22,
                "payroll_officer" => 307,
                "manhour_sign" => 22
            ),
            array(
                "id" => 4,
                "code" => "3399B",
                "payroll_code" => "3399B",
                "description" => "GO -Education & Employment Services Divisions",
                "department_initial" => "EESD",
                "payroll_title" => "Education & Employment Services",
                "department_head" => 1110,
                "payroll_officer" => 979,
                "manhour_sign" => 706
            ),
            array(
                "id" => 5,
                "code" => "4411",
                "payroll_code" => "4421E",
                "description" => "Oriental Mindoro Provincial Hospital",
                "department_initial" => "OMPH",
                "payroll_title" => "Hospital Services - OMPH",
                "department_head" => 867,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 6,
                "code" => "1011N",
                "payroll_code" => "1011A",
                "description" => "GO -Executive Assistant",
                "department_initial" => "MSSD",
                "payroll_title" => "Management Support Staff Services",
                "department_head" => 1110,
                "payroll_officer" => 700,
                "manhour_sign" => 697
            ),
            array(
                "id" => 7,
                "code" => "1011L",
                "payroll_code" => "9991",
                "description" => "Provincial Disaster Risk Reduction & Mgt. Office",
                "department_initial" => "PDRRMO",
                "payroll_title" => "Disaster Risk Reduction Management Services",
                "department_head" => 718,
                "payroll_officer" => 1302,
                "manhour_sign" => 718
            ),
            array(
                "id" => 8,
                "code" => "1011M",
                "payroll_code" => "3399B",
                "description" => "GO -Education & Employment Services Divisionss",
                "department_initial" => "EESD",
                "payroll_title" => "Education & Employment Services",
                "department_head" => 1110,
                "payroll_officer" => 719,
                "manhour_sign" => 706
            ),
            array(
                "id" => 9,
                "code" => "1032",
                "payroll_code" => "1032",
                "description" => "Provincial Human Resource Management Office",
                "department_initial" => "PHRMO",
                "payroll_title" => "Human Resource Management Services",
                "department_head" => 716,
                "payroll_officer" => 2,
                "manhour_sign" => 77
            ),
            array(
                "id" => 10,
                "code" => "1011",
                "payroll_code" => "1011",
                "description" => "Office of the Governor",
                "department_initial" => "GO",
                "payroll_title" => "Executive Services",
                "department_head" => 1110,
                "payroll_officer" => 700,
                "manhour_sign" => 670
            ),
            array(
                "id" => 11,
                "code" => "1021",
                "payroll_code" => "1021",
                "description" => "Office of the Vice Governor & Sangguniang Panlalawigan",
                "department_initial" => "SP",
                "payroll_title" => "Legislative Services",
                "department_head" => 185,
                "payroll_officer" => 180,
                "manhour_sign" => 185
            ),
            array(
                "id" => 12,
                "code" => "1031",
                "payroll_code" => "1031",
                "description" => "Provincial Administrator's Office",
                "department_initial" => "PA",
                "payroll_title" => "Administrative Management Services",
                "department_head" => 1318,
                "payroll_officer" => NULL,
                "manhour_sign" => 975
            ),
            array(
                "id" => 13,
                "code" => "1041",
                "payroll_code" => "1041",
                "description" => "Provincial Planning and Development Office",
                "department_initial" => "PPDO",
                "payroll_title" => "Planning and Development Services",
                "department_head" => 22,
                "payroll_officer" => 307,
                "manhour_sign" => 22
            ),
            array(
                "id" => 14,
                "code" => "1061",
                "payroll_code" => "1061",
                "description" => "Provincial General Services Office",
                "department_initial" => "GSO",
                "payroll_title" => "General Services",
                "department_head" => 405,
                "payroll_officer" => 379,
                "manhour_sign" => 540
            ),
            array(
                "id" => 15,
                "code" => "1071",
                "payroll_code" => "1071",
                "description" => "Provincial Budget Office",
                "department_initial" => "PBO",
                "payroll_title" => "Budgeting Services",
                "department_head" => 931,
                "payroll_officer" => 102,
                "manhour_sign" => 931
            ),
            array(
                "id" => 16,
                "code" => "1081",
                "payroll_code" => "1081",
                "description" => "Office of the Provincial Accountant",
                "department_initial" => "OPA",
                "payroll_title" => "Accounting Services",
                "department_head" => 991,
                "payroll_officer" => 173,
                "manhour_sign" => 991
            ),
            array(
                "id" => 17,
                "code" => "1091",
                "payroll_code" => "1091",
                "description" => "Provincial Treasurer's Office",
                "department_initial" => "PTO",
                "payroll_title" => "Treasury Services",
                "department_head" => 761,
                "payroll_officer" => 745,
                "manhour_sign" => 761
            ),
            array(
                "id" => 18,
                "code" => "1101",
                "payroll_code" => "1101",
                "description" => "Provincial Assessor's Office",
                "department_initial" => "PAsO",
                "payroll_title" => "Assessment Services",
                "department_head" => 1339,
                "payroll_officer" => 149,
                "manhour_sign" => 156
            ),
            array(
                "id" => 19,
                "code" => "1111",
                "payroll_code" => "",
                "description" => "Commission on Audit",
                "department_initial" => "COA",
                "payroll_title" => "",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 20,
                "code" => "1011A",
                "payroll_code" => "3399B",
                "description" => "Provincial Governor-EESD",
                "department_initial" => "EESD",
                "payroll_title" => "Education and Employment Services",
                "department_head" => 1110,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 21,
                "code" => "1013",
                "payroll_code" => "1061",
                "description" => "PGSO -Civil Security Division",
                "department_initial" => "CSD",
                "payroll_title" => "General Services",
                "department_head" => 405,
                "payroll_officer" => 1330,
                "manhour_sign" => 1331
            ),
            array(
                "id" => 22,
                "code" => "1011G",
                "payroll_code" => "8992",
                "description" => "Provincial Tourism,Investment & Enterprise Development Office",
                "department_initial" => "PTIEDO",
                "payroll_title" => "Tourism,Investment & Enterprise Development Services",
                "department_head" => 723,
                "payroll_officer" => 721,
                "manhour_sign" => 723
            ),
            array(
                "id" => 23,
                "code" => "1011H",
                "payroll_code" => "1011B",
                "description" => "GO -Internal Audit & Services Division",
                "department_initial" => "IASD",
                "payroll_title" => "Internal Audit Services",
                "department_head" => 1110,
                "payroll_officer" => 552,
                "manhour_sign" => 93
            ),
            array(
                "id" => 24,
                "code" => "1011I",
                "payroll_code" => "1011E",
                "description" => "GO -Provincial Detention Center Management Services Division",
                "department_initial" => "GO-PDCMSD",
                "payroll_title" => "Provincial Detention Center Management Services ",
                "department_head" => 1110,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 25,
                "code" => "1121",
                "payroll_code" => "1011C",
                "description" => "GO - Public Information Services Division",
                "department_initial" => "PISD",
                "payroll_title" => "Public Information Services",
                "department_head" => 1110,
                "payroll_officer" => 714,
                "manhour_sign" => 713
            ),
            array(
                "id" => 26,
                "code" => "1131",
                "payroll_code" => "1131",
                "description" => "Provincial Legal Office",
                "department_initial" => "PLO",
                "payroll_title" => "Legal Services",
                "department_head" => 1319,
                "payroll_officer" => 131,
                "manhour_sign" => 779
            ),
            array(
                "id" => 27,
                "code" => "7611",
                "payroll_code" => "7611",
                "description" => "Provincial Social Welfare & Development Office",
                "department_initial" => "PSWDO",
                "payroll_title" => "Social Welfare & Development Services",
                "department_head" => 203,
                "payroll_officer" => 198,
                "manhour_sign" => 203
            ),
            array(
                "id" => 28,
                "code" => "8711",
                "payroll_code" => "8711",
                "description" => "Provincial Agriculturist Office",
                "department_initial" => "PAgO",
                "payroll_title" => "Agricultural Services",
                "department_head" => 980,
                "payroll_officer" => 227,
                "manhour_sign" => 771
            ),
            array(
                "id" => 29,
                "code" => "8721",
                "payroll_code" => "8721",
                "description" => "Provincial Veterinary's Office",
                "department_initial" => "ProVet",
                "payroll_title" => "Veterinary Services",
                "department_head" => 141,
                "payroll_officer" => 236,
                "manhour_sign" => 154
            ),
            array(
                "id" => 30,
                "code" => "8731",
                "payroll_code" => "8731",
                "description" => "Provincial Environment & Natural Resources Office",
                "department_initial" => "ENRO",
                "payroll_title" => "Environment & Natural Resources Management Services",
                "department_head" => 342,
                "payroll_officer" => 329,
                "manhour_sign" => 902
            ),
            array(
                "id" => 31,
                "code" => "8751",
                "payroll_code" => "8751",
                "description" => "Provincial Engineering Office",
                "department_initial" => "PEO",
                "payroll_title" => "Engineering Services",
                "department_head" => 640,
                "payroll_officer" => 526,
                "manhour_sign" => 621
            ),
            array(
                "id" => 32,
                "code" => "1031A",
                "payroll_code" => "1031",
                "description" => "PA - Management Information Services Division",
                "department_initial" => "MIS",
                "payroll_title" => "Administrative Management Services",
                "department_head" => 1016,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 33,
                "code" => "2001",
                "payroll_code" => "",
                "description" => "Civil Service Commission",
                "department_initial" => "CSC",
                "payroll_title" => "",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 34,
                "code" => "2002",
                "payroll_code" => "",
                "description" => "Regional Trial Court",
                "department_initial" => "RTC",
                "payroll_title" => "",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 35,
                "code" => "2003",
                "payroll_code" => "",
                "description" => "GO - Language Learning Program",
                "department_initial" => "LSI",
                "payroll_title" => "",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 36,
                "code" => "2004",
                "payroll_code" => "",
                "description" => "GO -Extension Office",
                "department_initial" => "GO-EXT",
                "payroll_title" => "",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 37,
                "code" => "4411A",
                "payroll_code" => "4421C",
                "description" => "Oriental Mindoro Central District Hospital",
                "department_initial" => "OMCDH",
                "payroll_title" => "Hospital Services - OMCDH",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 38,
                "code" => "4411B",
                "payroll_code" => "4421D",
                "description" => "Oriental Mindoro Southern District Hospital",
                "department_initial" => "OMSDH",
                "payroll_title" => "Hospital Services - OMSDH",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 39,
                "code" => "4411C",
                "payroll_code" => "4421A",
                "description" => "Bulalacao Community Hospital",
                "department_initial" => "BCH",
                "payroll_title" => "Hospital Services - BCH",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 40,
                "code" => "2005",
                "payroll_code" => "8999B",
                "description" => "GO - BLOM",
                "department_initial" => "BLOM",
                "payroll_title" => "Pharmacy Services",
                "department_head" => 1110,
                "payroll_officer" => 412,
                "manhour_sign" => 1104
            ),
            array(
                "id" => 41,
                "code" => "411D",
                "payroll_code" => "4421B",
                "description" => "Naujan Community Hospital",
                "department_initial" => "NCH",
                "payroll_title" => "Hospital Services - NCH",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => 0
            ),
            array(
                "id" => 42,
                "code" => "1011Q",
                "payroll_code" => "1011A",
                "description" => "GO - Management Support Staff Division",
                "department_initial" => "GO-MSSD",
                "payroll_title" => "Management Support Staff Services",
                "department_head" => 1110,
                "payroll_officer" => 698,
                "manhour_sign" => 697
            ),
            array(
                "id" => 43,
                "code" => "4411D",
                "payroll_code" => "4411",
                "description" => "Provincial Health Office",
                "department_initial" => "PHO",
                "payroll_title" => "Health Services",
                "department_head" => 1318,
                "payroll_officer" => NULL,
                "manhour_sign" => NULL
            ),
            array(
                "id" => 44,
                "code" => "1041E",
                "payroll_code" => "1041",
                "description" => "PPDO-Research and Project Development Management Division",
                "department_initial" => "PPDO-Research",
                "payroll_title" => "Planning and Development Services",
                "department_head" => 22,
                "payroll_officer" => 307,
                "manhour_sign" => 22
            ),
            array(
                "id" => 45,
                "code" => "1041F",
                "payroll_code" => "1041",
                "description" => "PPDO-Monitoring and Evaluation Division",
                "department_initial" => "PPDO-Monitoring",
                "payroll_title" => "Planning and Development Services",
                "department_head" => 22,
                "payroll_officer" => 307,
                "manhour_sign" => 22
            ),
            array(
                "id" => 46,
                "code" => "4411E",
                "payroll_code" => "4411E",
                "description" => "OMPH - Drug Rehab Center",
                "department_initial" => "OMPH-DRC",
                "payroll_title" => "Drug Rehabilitation Center",
                "department_head" => NULL,
                "payroll_officer" => NULL,
                "manhour_sign" => NULL
            ),
            array(
                "id" => 65,
                "code" => "9999",
                "payroll_code" => "1121",
                "description" => "Provincial Information and  Communication Technology Office",
                "department_initial" => "PICTO",
                "payroll_title" => "Information and  Communication Technology Services",
                "department_head" => 1110,
                "payroll_officer" => NULL,
                "manhour_sign" => NULL
            ),
            array(
                "id" => 66,
                "code" => "9944",
                "payroll_code" => "3399",
                "description" => "Provincial Education, Labor and Employment and Youth and Sports",
                "department_initial" => "PELEYSO",
                "payroll_title" => "Education, Labor and Employment and Youth and Sports Services",
                "department_head" => 706,
                "payroll_officer" => NULL,
                "manhour_sign" => NULL
            ),
            array(
                "id" => 67,
                "code" => "9934",
                "payroll_code" => "8912",
                "description" => "Provincial Tourism and Cultural Affairs Office",
                "department_initial" => "PTCAO",
                "payroll_title" => "Tourism and Cultural Affairs Services",
                "department_head" => 1110,
                "payroll_officer" => NULL,
                "manhour_sign" => NULL
            ),
            array(
                "id" => 68,
                "code" => "9939",
                "payroll_code" => "8992",
                "description" => "Provincial Investment, Cooperative and Enterprise Development Office",
                "department_initial" => "PICEDO",
                "payroll_title" => "Investment, Cooperative and Enterprise Development Services",
                "department_head" => 1110,
                "payroll_officer" => 721,
                "manhour_sign" => 723
            ),
            array(
                "id" => 69,
                "code" => "1011J",
                "payroll_code" => "",
                "description" => "Galing at Serbisyo para sa MindoreÃ±o Action Center",
                "department_initial" => "GSMAC",
                "payroll_title" => "",
                "department_head" => 1328,
                "payroll_officer" => NULL,
                "manhour_sign" => NULL
            ),
            array(
                "id" => 130,
                "code" => "3399A",
                "payroll_code" => "",
                "description" => "Special Concern Divisions",
                "department_initial" => "SCD",
                "payroll_title" => "Special Concern Services",
                "department_head" => 1110,
                "payroll_officer" => NULL,
                "manhour_sign" => NULL
            ),
            array(
                "id" => 131,
                "code" => "1011R",
                "payroll_code" => "",
                "description" => "Provincial Social Action Center",
                "department_initial" => "PSAC",
                "payroll_title" => "",
                "department_head" => 1110,
                "payroll_officer" => NULL,
                "manhour_sign" => NULL
            ),
            array(
                "id" => 132,
                "code" => "OMBC",
                "payroll_code" => "",
                "description" => "Oriental Mindoro Blood Council",
                "department_initial" => "OMBC",
                "payroll_title" => "",
                "department_head" => 0,
                "payroll_officer" => NULL,
                "manhour_sign" => NULL
            ),
        );
    }
}
