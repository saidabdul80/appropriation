<?php
return [
    "table-css"=>'<style>table table {border-collapse: collapse;width: 100%;} table table th, td {padding: 8px;text-align: left;border-bottom: 1px solid #ddd;}</style>',
    "dynamic_data"=>[
        "Subject"=>["show"=>1,"activate"=>1,"value"=>'',"type"=>"text","for"=>''],
        "Section_of_Work_Plan"=>["show"=>1,"activate"=>0,"value"=>'',"type"=>"text","for"=>''],
        "File_Name"=>["show"=>1,"activate"=>0,"value"=>'',"type"=>"text","for"=>''],
        "File_Number"=>["show"=>1,"activate"=>0,"value"=>'',"type"=>"text","for"=>''],
        "Page_Number"=>["show"=>1,"activate"=>0,"value"=>'',"type"=>"text","for"=>''],
        "Beneficiary"=>["show"=>1,"activate"=>0,"value"=>'',"type"=>"text","for"=>''],
        "Account_Number"=>["show"=>1,"activate"=>0,"value"=>0,"type"=>"number","for"=>''],
        "Amount"=>["show"=>1,"activate"=>1,"value"=>0,"type"=>'number',"for"=>'tax',"amount"=>0],
        "Payment_Date"=>["show"=>1,"activate"=>1,"value"=>'',"type"=>"date","for"=>''],
        "Trx_Charges"=>["show"=>1,"activate"=>1,"value"=>0,"type"=>"number","for"=>'tax',"amount"=>0],
        "VAT_%"=>["show"=>1,"activate"=>0,"value"=>0,"type"=>"number","for"=>'tax',"amount"=>0],
        "VAT_₦"=>["show"=>0,"activate"=>0,"value"=>0,"type"=>"number","for"=>'tax',"amount"=>0],
        "Withholding_Tax_%"=>["show"=>1,"activate"=>0,"value"=>0,"type"=>"number","for"=>'tax',"amount"=>0],
        "Withholding_Tax_₦"=>["show"=>0,"activate"=>0,"value"=>0,"type"=>"number","for"=>'tax',"amount"=>0],
        "Stamp_Duty_%"=>["show"=>1,"activate"=>0,"value"=>0,"type"=>"number","for"=>'tax',"amount"=>0],
        "Stamp_Duty_₦"=>["show"=>0,"activate"=>0,"value"=>0,"type"=>"number","for"=>'tax',"amount"=>0],
        "Gross_Amount"=>["show"=>1,"activate"=>1,"value"=>0,"type"=>"number","for"=>'tax',"amount"=>0],
        "Total_Taxes"=>["show"=>1,"activate"=>1,"value"=>0,"type"=>"number","for"=>'tax',"amount"=>0]
    ]
];