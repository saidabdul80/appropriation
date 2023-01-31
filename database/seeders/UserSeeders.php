<?php

namespace Database\Seeders;

use App\Models\Appropriation;
use App\Models\Department;
use App\Models\Month;
use App\Models\Scheme;
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

      $departments = array(
        array('id' => '1','name' => 'DEPARTMENT OF ADMINISTRATION','short_name' => 'DA&FA','created_at' => NULL,'updated_at' => NULL),
        array('id' => '2','name' => 'DEPARTMENT OF POVIDER & BENEFIT MANAGEMENT','short_name' => 'DP&BM','created_at' => NULL,'updated_at' => NULL),
        array('id' => '3','name' => 'DEPARTMENT OF PROGRAMME & BUSINESS DEVELOPMENT','short_name' => 'DP&BD','created_at' => NULL,'updated_at' => NULL),
        array('id' => '4','name' => 'DEPARTMENT OF HEALTH INFORMATION COMMUNICATION TECHNOLOGY','short_name' => 'DHICT','created_at' => NULL,'updated_at' => NULL),
        array('id' => '5','name' => 'DEPARTMENT OF HEALTH PLANNING RESEARCH & STATISTIC','short_name' => 'DHPRS','created_at' => NULL,'updated_at' => NULL)
      );
      
      Department::insert($departments);

      $wallets = array(
        array('id' => '1','wallet_number' => '230129032939','owner_type' => 'App\\Models\\Scheme','owner_id' => '1','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source' => 'BHCPF Funding (FG)','description' => 'Total BHCPF Fund for the year 2023','created_at' => '2023-01-29 15:29:39','updated_at' => '2023-01-29 16:42:10','deleted_at' => NULL,'fund_month_year' => NULL),
        array('id' => '2','wallet_number' => '230129033002','owner_type' => 'App\\Models\\Scheme','owner_id' => '2','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source' => 'BHCPF Funding (FG)','description' => NULL,'created_at' => '2023-01-29 15:30:02','updated_at' => '2023-01-29 15:30:02','deleted_at' => NULL,'fund_month_year' => NULL),
        array('id' => '3','wallet_number' => '230129033017','owner_type' => 'App\\Models\\Scheme','owner_id' => '3','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source' => 'Formal Sector Premium','description' => 'Total monthly formal sector premium: Feb. 2023','created_at' => '2023-01-29 15:30:17','updated_at' => '2023-01-29 17:25:19','deleted_at' => NULL,'fund_month_year' => NULL),
        array('id' => '4','wallet_number' => '230129033023','owner_type' => 'App\\Models\\Scheme','owner_id' => '4','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source' => 'BHCPF Funding (FG)','description' => NULL,'created_at' => '2023-01-29 15:30:23','updated_at' => '2023-01-29 15:30:23','deleted_at' => NULL,'fund_month_year' => NULL),
        array('id' => '5','wallet_number' => '230129034848','owner_type' => 'App\\Models\\Scheme','owner_id' => '5','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source' => 'BHCPF Funding (FG)','description' => NULL,'created_at' => '2023-01-29 15:48:48','updated_at' => '2023-01-29 15:48:48','deleted_at' => NULL,'fund_month_year' => NULL),
        array('id' => '6','wallet_number' => '230129034846','owner_type' => 'App\\Models\\Scheme','owner_id' => '6','balance' => '0.00','safe_balance' => '0.00','total_collection' => '0.00','source' => 'BHCPF Funding (FG)','description' => NULL,'created_at' => '2023-01-29 15:48:48','updated_at' => '2023-01-29 15:48:48','deleted_at' => NULL,'fund_month_year' => NULL),
      );

      Wallet::insert($wallets);

      $appropriations = array(
        array('id' => '1','scheme_id' => '1','department_id' => '[2]','name' => 'Capitation','wallet_number' => NULL,'percentage_dividend' => '57.00','created_at' => '2023-01-29 15:45:44','updated_at' => '2023-01-29 15:45:44','deleted_at' => NULL),
        array('id' => '2','scheme_id' => '1','department_id' => '[2]','name' => 'FFS Claims','wallet_number' => NULL,'percentage_dividend' => '11.25','created_at' => '2023-01-29 15:46:10','updated_at' => '2023-01-29 15:46:10','deleted_at' => NULL),
        array('id' => '3','scheme_id' => '1','department_id' => '[1]','name' => 'Reserve','wallet_number' => NULL,'percentage_dividend' => '12.00','created_at' => '2023-01-29 15:46:36','updated_at' => '2023-01-29 15:46:36','deleted_at' => NULL),
        array('id' => '4','scheme_id' => '1','department_id' => '[1]','name' => 'Admin','wallet_number' => NULL,'percentage_dividend' => '5.00','created_at' => '2023-01-29 15:46:54','updated_at' => '2023-01-29 15:46:54','deleted_at' => NULL),
        array('id' => '5','scheme_id' => '1','department_id' => '[4]','name' => 'ICT Resources','wallet_number' => NULL,'percentage_dividend' => '9.75','created_at' => '2023-01-29 15:47:22','updated_at' => '2023-01-29 15:47:22','deleted_at' => NULL),
        array('id' => '6','scheme_id' => '1','department_id' => '[2,5]','name' => 'QA/M&E','wallet_number' => NULL,'percentage_dividend' => '5.00','created_at' => '2023-01-29 15:47:59','updated_at' => '2023-01-29 15:47:59','deleted_at' => NULL),
        array('id' => '7','scheme_id' => '5','department_id' => '[2]','name' => 'Capitation','wallet_number' => NULL,'percentage_dividend' => '57.00','created_at' => '2023-01-29 15:49:08','updated_at' => '2023-01-29 15:49:08','deleted_at' => NULL),
        array('id' => '8','scheme_id' => '5','department_id' => '[2]','name' => 'FFS Claims','wallet_number' => NULL,'percentage_dividend' => '11.25','created_at' => '2023-01-29 15:49:19','updated_at' => '2023-01-29 15:49:19','deleted_at' => NULL),
        array('id' => '9','scheme_id' => '5','department_id' => '[1]','name' => 'Reserve','wallet_number' => NULL,'percentage_dividend' => '12.00','created_at' => '2023-01-29 15:49:34','updated_at' => '2023-01-29 15:49:34','deleted_at' => NULL),
        array('id' => '10','scheme_id' => '5','department_id' => '[1]','name' => 'Admin','wallet_number' => NULL,'percentage_dividend' => '5.00','created_at' => '2023-01-29 15:49:43','updated_at' => '2023-01-29 15:49:43','deleted_at' => NULL),
        array('id' => '11','scheme_id' => '5','department_id' => '[4]','name' => 'ICT Resources','wallet_number' => NULL,'percentage_dividend' => '9.75','created_at' => '2023-01-29 15:50:10','updated_at' => '2023-01-29 15:50:10','deleted_at' => NULL),
        array('id' => '12','scheme_id' => '5','department_id' => '[2,5]','name' => 'QA/M&E','wallet_number' => NULL,'percentage_dividend' => '5.00','created_at' => '2023-01-29 15:50:45','updated_at' => '2023-01-29 15:50:45','deleted_at' => NULL),
        array('id' => '13','scheme_id' => '2','department_id' => '[2]','name' => 'Capitation','wallet_number' => NULL,'percentage_dividend' => '60.00','created_at' => '2023-01-29 15:52:06','updated_at' => '2023-01-29 15:52:06','deleted_at' => NULL),
        array('id' => '14','scheme_id' => '2','department_id' => '[2]','name' => 'FFS Claims','wallet_number' => NULL,'percentage_dividend' => '17.00','created_at' => '2023-01-29 15:52:17','updated_at' => '2023-01-29 15:52:17','deleted_at' => NULL),
        array('id' => '15','scheme_id' => '2','department_id' => '[2]','name' => 'TPA Admin','wallet_number' => NULL,'percentage_dividend' => '9.00','created_at' => '2023-01-29 15:52:33','updated_at' => '2023-01-29 15:52:33','deleted_at' => NULL),
        array('id' => '16','scheme_id' => '2','department_id' => '[4]','name' => 'HICT Admin','wallet_number' => NULL,'percentage_dividend' => '3.00','created_at' => '2023-01-29 15:52:59','updated_at' => '2023-01-29 15:52:59','deleted_at' => NULL),
        array('id' => '17','scheme_id' => '2','department_id' => '[2]','name' => 'Medical Savings','wallet_number' => NULL,'percentage_dividend' => '4.00','created_at' => '2023-01-29 15:53:25','updated_at' => '2023-01-29 15:53:25','deleted_at' => NULL),
        array('id' => '18','scheme_id' => '2','department_id' => '[1]','name' => 'NiCare Admin','wallet_number' => NULL,'percentage_dividend' => '7.00','created_at' => '2023-01-29 15:53:50','updated_at' => '2023-01-29 15:53:50','deleted_at' => NULL),
        array('id' => '19','scheme_id' => '3','department_id' => '[2]','name' => 'Capitation','wallet_number' => NULL,'percentage_dividend' => '60.00','created_at' => '2023-01-29 15:54:11','updated_at' => '2023-01-29 15:54:11','deleted_at' => NULL),
        array('id' => '20','scheme_id' => '3','department_id' => '[2]','name' => 'FFS Claims','wallet_number' => NULL,'percentage_dividend' => '17.00','created_at' => '2023-01-29 15:54:57','updated_at' => '2023-01-29 15:54:57','deleted_at' => NULL),
        array('id' => '21','scheme_id' => '3','department_id' => '[4]','name' => 'HICT Admin','wallet_number' => NULL,'percentage_dividend' => '6.00','created_at' => '2023-01-29 15:58:34','updated_at' => '2023-01-29 15:58:34','deleted_at' => NULL),
        array('id' => '22','scheme_id' => '3','department_id' => '[2]','name' => 'Medical Saving','wallet_number' => NULL,'percentage_dividend' => '4.00','created_at' => '2023-01-29 15:59:22','updated_at' => '2023-01-29 15:59:22','deleted_at' => NULL),
        array('id' => '23','scheme_id' => '3','department_id' => '[1]','name' => 'NiCare Admin (General)','wallet_number' => NULL,'percentage_dividend' => '6.00','created_at' => '2023-01-29 16:00:27','updated_at' => '2023-01-29 16:00:27','deleted_at' => NULL),
        array('id' => '24','scheme_id' => '3','department_id' => '[2]','name' => 'Quality Assurance','wallet_number' => NULL,'percentage_dividend' => '1.50','created_at' => '2023-01-29 16:00:51','updated_at' => '2023-01-29 16:00:51','deleted_at' => NULL),
        array('id' => '25','scheme_id' => '3','department_id' => '[5]','name' => 'M&E','wallet_number' => NULL,'percentage_dividend' => '1.50','created_at' => '2023-01-29 16:01:04','updated_at' => '2023-01-29 16:01:04','deleted_at' => NULL),
        array('id' => '26','scheme_id' => '3','department_id' => '[3]','name' => 'Mobilization & Sensitization','wallet_number' => NULL,'percentage_dividend' => '1.00','created_at' => '2023-01-29 16:01:35','updated_at' => '2023-01-29 16:01:35','deleted_at' => NULL),
        array('id' => '27','scheme_id' => '3','department_id' => '[3]','name' => 'NiCare DOs (MDAs/LGAs)','wallet_number' => NULL,'percentage_dividend' => '1.00','created_at' => '2023-01-29 16:02:22','updated_at' => '2023-01-29 16:02:22','deleted_at' => NULL),
        array('id' => '28','scheme_id' => '3','department_id' => '[2,3]','name' => 'HMU&CCMS Agent Mgt.','wallet_number' => NULL,'percentage_dividend' => '1.00','created_at' => '2023-01-29 16:03:17','updated_at' => '2023-01-29 16:03:17','deleted_at' => NULL),
        array('id' => '29','scheme_id' => '3','department_id' => '[1]','name' => 'Acct. & FSC','wallet_number' => NULL,'percentage_dividend' => '1.00','created_at' => '2023-01-29 16:05:03','updated_at' => '2023-01-29 16:05:03','deleted_at' => NULL)
      );
      
      Appropriation::insert($appropriations);
      
    }
}
