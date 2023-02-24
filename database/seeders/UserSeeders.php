<?php

namespace Database\Seeders;

use App\Models\Appropriation;
use App\Models\AppropriationType;
use App\Models\Department;
use App\Models\Fund;
use App\Models\Month;
use App\Models\Scheme;
use App\Models\Source;
use App\Models\Task;
use App\Models\Unit;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $schemes = array(
        array('id' => '1','name' => 'BHCPF','wallet_number' => '230129032939','premium_amount' => 12000,'created_at' => '2023-01-29 15:29:39','updated_at' => '2023-01-29 15:29:39','fund_category' => 'year'),
        array('id' => '2','name' => 'NiCare (Informal Sector)','wallet_number' => '230129033002','premium_amount' => 7200,'created_at' => '2023-01-29 15:30:02','updated_at' => '2023-01-29 15:30:02','fund_category' => 'month'),
        array('id' => '3','name' => 'NiCare (Formal Sector)','wallet_number' => '230129033017','premium_amount' => 3600,'created_at' => '2023-01-29 15:30:17','updated_at' => '2023-01-29 15:30:17','fund_category' => 'year'),
        array('id' => '4','name' => 'NiCare TiSHIP','wallet_number' => '230129033023','premium_amount' => 3000,'created_at' => '2023-01-29 15:30:23','updated_at' => '2023-01-29 15:30:23','fund_category' => 'year'),
        array('id' => '5','name' => 'State Counterpart','wallet_number' => '230129034848','premium_amount' => 2000,'created_at' => '2023-01-29 15:48:48','updated_at' => '2023-01-29 15:48:48','fund_category' => 'year'),
        array('id' => '6','name' => 'Sample','wallet_number' => '230130042524','premium_amount' => 2000,'created_at' => '2023-01-30 04:25:24','updated_at' => '2023-01-30 04:25:24','fund_category' => 'month')
      );
      Scheme::insert($schemes);   
      Scheme::where('id',2)->update(['fund_type'=>'api']); 

      $departments = array(
        array('id' => '1','name' => 'DEPARTMENT OF ADMINISTRATION','short_name' => 'DA&FA','created_at' => NULL,'updated_at' => NULL),
        array('id' => '2','name' => 'DEPARTMENT OF POVIDER & BENEFIT MANAGEMENT','short_name' => 'DP&BM','created_at' => NULL,'updated_at' => NULL),
        array('id' => '3','name' => 'DEPARTMENT OF PROGRAMME & BUSINESS DEVELOPMENT','short_name' => 'DP&BD','created_at' => NULL,'updated_at' => NULL),
        array('id' => '4','name' => 'DEPARTMENT OF HEALTH INFORMATION COMMUNICATION TECHNOLOGY','short_name' => 'DHICT','created_at' => NULL,'updated_at' => NULL),
        array('id' => '5','name' => 'DEPARTMENT OF HEALTH PLANNING RESEARCH & STATISTIC','short_name' => 'DHPRS','created_at' => NULL,'updated_at' => NULL)
      );
      
      Department::insert($departments);

      $wallets = array(
        array('id' => '1', 'wallet_number' => '230129032939','owner_type' => 'App\\Models\\Scheme','owner_id' => '1','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source_id' =>NULL,'description' => 'Total BHCPF Fund for the year 2023','created_at' => '2023-01-29 15:29:39','updated_at' => '2023-01-29 16:42:10','deleted_at' => NULL,'fund_month_year' => NULL,'token'=>'1'),
        array('id' => '2', 'wallet_number' => '230129033002','owner_type' => 'App\\Models\\Scheme','owner_id' => '2','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source_id' =>NULL,'description' => NULL,'created_at' => '2023-01-29 15:30:02','updated_at' => '2023-01-29 15:30:02','deleted_at' => NULL,'fund_month_year' => NULL,'token'=>'2'),
        array('id' => '3', 'wallet_number' => '230129033017','owner_type' => 'App\\Models\\Scheme','owner_id' => '3','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source_id' =>NULL,'description' => 'Total monthly formal sector premium: Feb. 2023','created_at' => '2023-01-29 15:30:17','updated_at' => '2023-01-29 17:25:19','deleted_at' => NULL,'fund_month_year' => NULL,'token'=>'3'),
        array('id' => '4', 'wallet_number' => '230129033023','owner_type' => 'App\\Models\\Scheme','owner_id' => '4','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source_id' =>NULL,'description' => NULL,'created_at' => '2023-01-29 15:30:23','updated_at' => '2023-01-29 15:30:23','deleted_at' => NULL,'fund_month_year' => NULL,'token'=>'4'),
        array('id' => '5', 'wallet_number' => '230129034848','owner_type' => 'App\\Models\\Scheme','owner_id' => '5','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source_id' =>NULL,'description' => NULL,'created_at' => '2023-01-29 15:48:48','updated_at' => '2023-01-29 15:48:48','deleted_at' => NULL,'fund_month_year' => NULL,'token'=>'5'),
        array('id' => '6', 'wallet_number' => '230129034846','owner_type' => 'App\\Models\\Scheme','owner_id' => '6','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source_id' =>NULL,'description' => NULL,'created_at' => '2023-01-29 15:48:48','updated_at' => '2023-01-29 15:48:48','deleted_at' => NULL,'fund_month_year' => NULL,'token'=>'6'),
      );

      Wallet::insert($wallets);

      $appropriations = array(
        array('id' => '1','scheme_id' => '1','department_id' => '[2]','percentage_dividend' => '57.00','created_at' => '2023-01-29 15:45:44','updated_at' => '2023-01-29 15:45:44','deleted_at' => NULL,'appropriation_type_id' => '1'),
        array('id' => '2','scheme_id' => '1','department_id' => '[2]','percentage_dividend' => '11.25','created_at' => '2023-01-29 15:46:10','updated_at' => '2023-01-29 15:46:10','deleted_at' => NULL,'appropriation_type_id' => '2'),
        array('id' => '3','scheme_id' => '1','department_id' => '[1]','percentage_dividend' => '12.00','created_at' => '2023-01-29 15:46:36','updated_at' => '2023-01-29 15:46:36','deleted_at' => NULL,'appropriation_type_id' => '3'),
        array('id' => '4','scheme_id' => '1','department_id' => '[1]','percentage_dividend' => '5.00','created_at' => '2023-01-29 15:46:54','updated_at' => '2023-01-29 15:46:54','deleted_at' => NULL,'appropriation_type_id' => '4'),
        array('id' => '5','scheme_id' => '1','department_id' => '[4]','percentage_dividend' => '9.75','created_at' => '2023-01-29 15:47:22','updated_at' => '2023-01-29 15:47:22','deleted_at' => NULL,'appropriation_type_id' => '5'),
        array('id' => '6','scheme_id' => '1','department_id' => '[2,5]','percentage_dividend' => '5.00','created_at' => '2023-01-29 15:47:59','updated_at' => '2023-01-29 15:47:59','deleted_at' => NULL,'appropriation_type_id' => '6'),
        array('id' => '7','scheme_id' => '5','department_id' => '[2]','percentage_dividend' => '57.00','created_at' => '2023-01-29 15:49:08','updated_at' => '2023-01-29 15:49:08','deleted_at' => NULL,'appropriation_type_id' => '1'),
        array('id' => '8','scheme_id' => '5','department_id' => '[2]','percentage_dividend' => '11.25','created_at' => '2023-01-29 15:49:19','updated_at' => '2023-01-29 15:49:19','deleted_at' => NULL,'appropriation_type_id' => '2'),
        array('id' => '9','scheme_id' => '5','department_id' => '[1]','percentage_dividend' => '12.00','created_at' => '2023-01-29 15:49:34','updated_at' => '2023-01-29 15:49:34','deleted_at' => NULL,'appropriation_type_id' => '3'),
        array('id' => '10','scheme_id' => '5','department_id' => '[1]','percentage_dividend' => '5.00','created_at' => '2023-01-29 15:49:43','updated_at' => '2023-01-29 15:49:43','deleted_at' => NULL,'appropriation_type_id' => '4'),
        array('id' => '11','scheme_id' => '5','department_id' => '[4]','percentage_dividend' => '9.75','created_at' => '2023-01-29 15:50:10','updated_at' => '2023-01-29 15:50:10','deleted_at' => NULL,'appropriation_type_id' => '5'),
        array('id' => '12','scheme_id' => '5','department_id' => '[2,5]','percentage_dividend' => '5.00','created_at' => '2023-01-29 15:50:45','updated_at' => '2023-01-29 15:50:45','deleted_at' => NULL,'appropriation_type_id' => '6'),
        array('id' => '13','scheme_id' => '2','department_id' => '[2]','percentage_dividend' => '60.00','created_at' => '2023-01-29 15:52:06','updated_at' => '2023-01-29 15:52:06','deleted_at' => NULL,'appropriation_type_id' => '1'),
        array('id' => '14','scheme_id' => '2','department_id' => '[2]','percentage_dividend' => '17.00','created_at' => '2023-01-29 15:52:17','updated_at' => '2023-01-29 15:52:17','deleted_at' => NULL,'appropriation_type_id' => '2'),
        array('id' => '15','scheme_id' => '2','department_id' => '[2]','percentage_dividend' => '9.00','created_at' => '2023-01-29 15:52:33','updated_at' => '2023-01-29 15:52:33','deleted_at' => NULL,'appropriation_type_id' => '7'),
        array('id' => '16','scheme_id' => '2','department_id' => '[4]','percentage_dividend' => '3.00','created_at' => '2023-01-29 15:52:59','updated_at' => '2023-01-29 15:52:59','deleted_at' => NULL,'appropriation_type_id' => '8'),
        array('id' => '17','scheme_id' => '2','department_id' => '[2]','percentage_dividend' => '4.00','created_at' => '2023-01-29 15:53:25','updated_at' => '2023-01-29 15:53:25','deleted_at' => NULL,'appropriation_type_id' => '9'),
        array('id' => '18','scheme_id' => '2','department_id' => '[1]','percentage_dividend' => '7.00','created_at' => '2023-01-29 15:53:50','updated_at' => '2023-01-29 15:53:50','deleted_at' => NULL,'appropriation_type_id' => '10'),
        array('id' => '19','scheme_id' => '3','department_id' => '[2]','percentage_dividend' => '60.00','created_at' => '2023-01-29 15:54:11','updated_at' => '2023-01-29 15:54:11','deleted_at' => NULL,'appropriation_type_id' => '1'),
        array('id' => '20','scheme_id' => '3','department_id' => '[2]','percentage_dividend' => '17.00','created_at' => '2023-01-29 15:54:57','updated_at' => '2023-01-29 15:54:57','deleted_at' => NULL,'appropriation_type_id' => '2'),
        array('id' => '21','scheme_id' => '3','department_id' => '[4]','percentage_dividend' => '6.00','created_at' => '2023-01-29 15:58:34','updated_at' => '2023-01-29 15:58:34','deleted_at' => NULL,'appropriation_type_id' => '8'),
        array('id' => '22','scheme_id' => '3','department_id' => '[2]','percentage_dividend' => '4.00','created_at' => '2023-01-29 15:59:22','updated_at' => '2023-01-29 15:59:22','deleted_at' => NULL,'appropriation_type_id' => '11'),
        array('id' => '23','scheme_id' => '3','department_id' => '[1]','percentage_dividend' => '6.00','created_at' => '2023-01-29 16:00:27','updated_at' => '2023-01-29 16:00:27','deleted_at' => NULL,'appropriation_type_id' => '12'),
        array('id' => '24','scheme_id' => '3','department_id' => '[2]','percentage_dividend' => '1.50','created_at' => '2023-01-29 16:00:51','updated_at' => '2023-01-29 16:00:51','deleted_at' => NULL,'appropriation_type_id' => '13'),
        array('id' => '25','scheme_id' => '3','department_id' => '[5]','percentage_dividend' => '1.50','created_at' => '2023-01-29 16:01:04','updated_at' => '2023-01-29 16:01:04','deleted_at' => NULL,'appropriation_type_id' => '14'),
        array('id' => '26','scheme_id' => '3','department_id' => '[3]','percentage_dividend' => '1.00','created_at' => '2023-01-29 16:01:35','updated_at' => '2023-01-29 16:01:35','deleted_at' => NULL,'appropriation_type_id' => '15'),
        array('id' => '27','scheme_id' => '3','department_id' => '[3]','percentage_dividend' => '1.00','created_at' => '2023-01-29 16:02:22','updated_at' => '2023-01-29 16:02:22','deleted_at' => NULL,'appropriation_type_id' => '16'),
        array('id' => '28','scheme_id' => '3','department_id' => '[2,3]','percentage_dividend' => '1.00','created_at' => '2023-01-29 16:03:17','updated_at' => '2023-01-29 16:03:17','deleted_at' => NULL,'appropriation_type_id' => '17'),
        array('id' => '29','scheme_id' => '3','department_id' => '[1]','percentage_dividend' => '1.00','created_at' => '2023-01-29 16:05:03','updated_at' => '2023-01-29 16:05:03','deleted_at' => NULL,'appropriation_type_id' => '18'),
        array('id' => '30','scheme_id' => '4','department_id' => '[1]','percentage_dividend' => '7.00','created_at' => '2023-02-02 09:50:37','updated_at' => '2023-02-02 09:50:37','deleted_at' => NULL,'appropriation_type_id' => '10'),
        array('id' => '31','scheme_id' => '4','department_id' => '[2]','percentage_dividend' => '9.00','created_at' => '2023-02-02 09:50:57','updated_at' => '2023-02-02 09:50:57','deleted_at' => NULL,'appropriation_type_id' => '7'),
        array('id' => '32','scheme_id' => '4','department_id' => '[4]','percentage_dividend' => '3.00','created_at' => '2023-02-02 09:51:28','updated_at' => '2023-02-02 09:51:28','deleted_at' => NULL,'appropriation_type_id' => '19'),
        array('id' => '33','scheme_id' => '4','department_id' => '[2]','percentage_dividend' => '17.00','created_at' => '2023-02-02 09:51:46','updated_at' => '2023-02-02 09:51:46','deleted_at' => NULL,'appropriation_type_id' => '20'),
        array('id' => '34','scheme_id' => '4','department_id' => '[2]','percentage_dividend' => '60.00','created_at' => '2023-02-02 09:52:06','updated_at' => '2023-02-02 09:52:06','deleted_at' => NULL,'appropriation_type_id' => '1'),
        array('id' => '35','scheme_id' => '4','department_id' => '[1]','percentage_dividend' => '4.00','created_at' => '2023-02-02 09:52:49','updated_at' => '2023-02-02 09:52:49','deleted_at' => NULL,'appropriation_type_id' => '21')
      );      
      Appropriation::insert($appropriations);

      $appropriation_types = array(
        array('id' => '1','name' => 'Capitation','created_at' => NULL,'updated_at' => NULL),
        array('id' => '2','name' => 'FFS Claims','created_at' => NULL,'updated_at' => NULL),
        array('id' => '3','name' => 'Reserve','created_at' => NULL,'updated_at' => NULL),
        array('id' => '4','name' => 'Admin','created_at' => NULL,'updated_at' => NULL),
        array('id' => '5','name' => 'ICT Resources','created_at' => NULL,'updated_at' => NULL),
        array('id' => '6','name' => 'QA/M&E','created_at' => NULL,'updated_at' => NULL),
        array('id' => '7','name' => 'TPA Admin','created_at' => NULL,'updated_at' => NULL),
        array('id' => '8','name' => 'HICT Admin','created_at' => NULL,'updated_at' => NULL),
        array('id' => '9','name' => 'Medical Savings','created_at' => NULL,'updated_at' => NULL),
        array('id' => '10','name' => 'NiCare Admin','created_at' => NULL,'updated_at' => NULL),
        array('id' => '11','name' => 'Medical Saving','created_at' => NULL,'updated_at' => NULL),
        array('id' => '12','name' => 'NiCare Admin (General)','created_at' => NULL,'updated_at' => NULL),
        array('id' => '13','name' => 'Quality Assurance','created_at' => NULL,'updated_at' => NULL),
        array('id' => '14','name' => 'M&E','created_at' => NULL,'updated_at' => NULL),
        array('id' => '15','name' => 'Mobilization & Sensitization','created_at' => NULL,'updated_at' => NULL),
        array('id' => '16','name' => 'NiCare DOs (MDAs/LGAs)','created_at' => NULL,'updated_at' => NULL),
        array('id' => '17','name' => 'HMU&CCMS Agent Mgt.','created_at' => NULL,'updated_at' => NULL),
        array('id' => '18','name' => 'Acct. & FSC','created_at' => NULL,'updated_at' => NULL),
        array('id' => '19','name' => 'ICT Admin','created_at' => NULL,'updated_at' => NULL),
        array('id' => '20','name' => 'FFS','created_at' => NULL,'updated_at' => NULL),
        array('id' => '21','name' => 'TiSHIP Mgt. Committee','created_at' => NULL,'updated_at' => NULL)
      );

      AppropriationType::insert($appropriation_types);
      $sources = [
        [
          "name"=>'BHCPF Funding (FG)',
          "short_name"=>"",
          "scheme_id"=>1
        ],
        [
          "name"=>'State Counterpart Funding',
          "short_name"=>"",
          "scheme_id"=>5
        ],
        [
          "name"=>'Premium Sales',
          "short_name"=>"",
          "scheme_id"=>2
        ],
        [
          "name"=>'Formal Sector Premium',
          "short_name"=>"",
          "scheme_id"=>3
        ],
        [
          "name"=>'Other Source',
          "short_name"=>"",
          "scheme_id"=>1
        ],
        [
          "name"=>'Other Source',
          "short_name"=>"",
          "scheme_id"=>2
        ],
        [
          "name"=>'Other Source',
          "short_name"=>"",
          "scheme_id"=>3
        ],
        [
          "name"=>'Other Source',
          "short_name"=>"",
          "scheme_id"=>4
        ],
        [
          "name"=>'Other Source',
          "short_name"=>"",
          "scheme_id"=>4
        ],
        [
          "name"=>'Ibrahim Badamasi Babangida University Lapai',
          "short_name"=>"IBBUL",
          "scheme_id"=>4
        ],
        [
          "name"=>'Federal Poly Bida',
          "short_name"=>"",
          "scheme_id"=>4
        ],
        [
          "name"=>'Federal University of Technology Minna',
          "short_name"=>"FUT Minna",
          "scheme_id"=>4
        ],
        [
          "name"=>'Other Source',
          "short_name"=>"",
          "scheme_id"=>5
        ]

      ];       
      Source::insert($sources);
      $funds = array(
        array('id' => '1','fund_category' => '2020-07','scheme_id' => '2','amount' => '','status' => 'used','created_at' => NULL,'updated_at' => '2023-02-05 14:07:17'),
        array('id' => '2','fund_category' => '2021-01','scheme_id' => '2','amount' => '','status' => 'unused','created_at' => NULL,'updated_at' => NULL),
        array('id' => '3','fund_category' => '2021-02','scheme_id' => '2','amount' => '','status' => 'used','created_at' => NULL,'updated_at' => '2023-02-05 14:25:01'),
        array('id' => '4','fund_category' => '2021-03','scheme_id' => '2','amount' => '','status' => 'used','created_at' => NULL,'updated_at' => '2023-02-05 14:28:20'),
        array('id' => '5','fund_category' => '2021-04','scheme_id' => '2','amount' => '','status' => 'used','created_at' => NULL,'updated_at' => '2023-02-05 15:27:41'),
        array('id' => '6','fund_category' => '2021-05','scheme_id' => '2','amount' => '','status' => 'unused','created_at' => NULL,'updated_at' => NULL)
      );
      Fund::insert($funds);

      $permissions = array(
        array('id' => '11','name' => 'fund_scheme','guard_name' => 'web','description' => 'Fund Programme','created_at' => NULL,'updated_at' => NULL),
        array('id' => '12','name' => 'projection','guard_name' => 'web','description' => 'Projection','created_at' => NULL,'updated_at' => NULL),
        array('id' => '13','name' => 'appropriate','guard_name' => 'web','description' => 'Appropriate','created_at' => NULL,'updated_at' => NULL),
        array('id' => '14','name' => 'new_appropriationm','guard_name' => 'web','description' => 'New Appropriationm','created_at' => NULL,'updated_at' => NULL),
        array('id' => '15','name' => 'appr_income','guard_name' => 'web','description' => 'Appropriation Income','created_at' => NULL,'updated_at' => NULL),
        array('id' => '16','name' => 'appr_current_balance','guard_name' => 'web','description' => 'Appropriation current balance','created_at' => NULL,'updated_at' => NULL),
        array('id' => '17','name' => 'income','guard_name' => 'web','description' => 'Income','created_at' => NULL,'updated_at' => NULL),
        array('id' => '18','name' => 'balance','guard_name' => 'web','description' => 'Balance','created_at' => NULL,'updated_at' => NULL),
        array('id' => '19','name' => 'expenditure','guard_name' => 'web','description' => 'Expenditure','created_at' => NULL,'updated_at' => NULL),
        array('id' => '20','name' => 'general._app._history','guard_name' => 'web','description' => 'General App History','created_at' => NULL,'updated_at' => NULL),
        array('id' => '21','name' => 'debit_fund','guard_name' => 'web','description' => 'Debit Fund','created_at' => NULL,'updated_at' => NULL),
        array('id' => '22','name' => 'report','guard_name' => 'web','description' => 'Report','created_at' => NULL,'updated_at' => NULL),
        array('id' => '23','name' => 'appro_history','guard_name' => 'web','description' => 'Appropriation History','created_at' => NULL,'updated_at' => NULL)
      );

      Permission::insert($permissions);

      $roles = array(
        array('id' => '1','name' => 'Admin','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '2','name' => 'Director','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '3','name' => 'Chirman','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '4','name' => 'Member','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '5','name' => 'ES','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '6','name' => 'DHICT','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '7','name' => 'DA&FA','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '8','name' => 'DP&BM','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '9','name' => 'DP&BD','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '10','name' => 'DHPRS','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '11','name' => 'Chief Acct','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '12','name' => 'CIA','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '13','name' => 'Cashier','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL),
        array('id' => '14','name' => 'SA/DDHICT','guard_name' => 'web','description' => NULL,'created_at' => NULL,'updated_at' => NULL)
      );

      Role::insert($roles);

      $role_has_permissions = array(
        array('permission_id' => '11','role_id' => '11'),
        array('permission_id' => '11','role_id' => '12'),
        array('permission_id' => '11','role_id' => '13'),
        array('permission_id' => '12','role_id' => '6'),
        array('permission_id' => '12','role_id' => '7'),
        array('permission_id' => '12','role_id' => '8'),
        array('permission_id' => '12','role_id' => '9'),
        array('permission_id' => '12','role_id' => '10'),
        array('permission_id' => '12','role_id' => '11'),
        array('permission_id' => '12','role_id' => '13'),
        array('permission_id' => '12','role_id' => '14'),
        array('permission_id' => '13','role_id' => '11'),
        array('permission_id' => '13','role_id' => '13'),
        array('permission_id' => '14','role_id' => '6'),
        array('permission_id' => '14','role_id' => '7'),
        array('permission_id' => '14','role_id' => '8'),
        array('permission_id' => '14','role_id' => '9'),
        array('permission_id' => '14','role_id' => '10'),
        array('permission_id' => '14','role_id' => '11'),
        array('permission_id' => '14','role_id' => '12'),
        array('permission_id' => '14','role_id' => '13'),
        array('permission_id' => '14','role_id' => '14'),
        array('permission_id' => '15','role_id' => '5'),
        array('permission_id' => '15','role_id' => '6'),
        array('permission_id' => '15','role_id' => '7'),
        array('permission_id' => '15','role_id' => '8'),
        array('permission_id' => '15','role_id' => '9'),
        array('permission_id' => '15','role_id' => '10'),
        array('permission_id' => '15','role_id' => '11'),
        array('permission_id' => '15','role_id' => '12'),
        array('permission_id' => '15','role_id' => '13'),
        array('permission_id' => '16','role_id' => '5'),
        array('permission_id' => '16','role_id' => '6'),
        array('permission_id' => '16','role_id' => '7'),
        array('permission_id' => '16','role_id' => '8'),
        array('permission_id' => '16','role_id' => '9'),
        array('permission_id' => '16','role_id' => '10'),
        array('permission_id' => '16','role_id' => '11'),
        array('permission_id' => '16','role_id' => '12'),
        array('permission_id' => '16','role_id' => '13'),
        array('permission_id' => '17','role_id' => '5'),
        array('permission_id' => '17','role_id' => '6'),
        array('permission_id' => '17','role_id' => '7'),
        array('permission_id' => '17','role_id' => '8'),
        array('permission_id' => '17','role_id' => '9'),
        array('permission_id' => '17','role_id' => '10'),
        array('permission_id' => '17','role_id' => '11'),
        array('permission_id' => '17','role_id' => '12'),
        array('permission_id' => '17','role_id' => '13'),
        array('permission_id' => '18','role_id' => '5'),
        array('permission_id' => '18','role_id' => '6'),
        array('permission_id' => '18','role_id' => '7'),
        array('permission_id' => '18','role_id' => '8'),
        array('permission_id' => '18','role_id' => '9'),
        array('permission_id' => '18','role_id' => '10'),
        array('permission_id' => '18','role_id' => '11'),
        array('permission_id' => '18','role_id' => '12'),
        array('permission_id' => '18','role_id' => '13'),
        array('permission_id' => '19','role_id' => '5'),
        array('permission_id' => '19','role_id' => '6'),
        array('permission_id' => '19','role_id' => '7'),
        array('permission_id' => '19','role_id' => '8'),
        array('permission_id' => '19','role_id' => '9'),
        array('permission_id' => '19','role_id' => '10'),
        array('permission_id' => '19','role_id' => '11'),
        array('permission_id' => '19','role_id' => '12'),
        array('permission_id' => '19','role_id' => '13'),
        array('permission_id' => '20','role_id' => '5'),
        array('permission_id' => '20','role_id' => '6'),
        array('permission_id' => '20','role_id' => '7'),
        array('permission_id' => '20','role_id' => '8'),
        array('permission_id' => '20','role_id' => '9'),
        array('permission_id' => '20','role_id' => '10'),
        array('permission_id' => '20','role_id' => '11'),
        array('permission_id' => '20','role_id' => '12'),
        array('permission_id' => '20','role_id' => '13'),
        array('permission_id' => '22','role_id' => '5'),
        array('permission_id' => '22','role_id' => '6'),
        array('permission_id' => '22','role_id' => '7'),
        array('permission_id' => '22','role_id' => '8'),
        array('permission_id' => '22','role_id' => '9'),
        array('permission_id' => '22','role_id' => '10'),
        array('permission_id' => '22','role_id' => '11'),
        array('permission_id' => '22','role_id' => '12'),
        array('permission_id' => '22','role_id' => '13'),
        array('permission_id' => '23','role_id' => '11'),
        array('permission_id' => '23','role_id' => '13')
      );

      DB::table('role_has_permissions')->insert($role_has_permissions);
      
    }
}
