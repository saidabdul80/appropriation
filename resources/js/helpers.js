// resources/js/helpers.js

export const helpers = {
    closeModal:(callback)=>{
        callback()
    },
    getIndexOf(arr,scheme,id='id') {      
      if (!arr.every(item => id in item && id in scheme)) {
        return {}
      }
      
      try {
        return arr.findIndex(item => item[id] === scheme[id]);
      } catch (error) {        
        return {}
      }
    },
    currency: (amount, convert=true) => {
      if(!convert){
        return amount;
      }
      if (amount) {
        let res = new Intl.NumberFormat('NGN', {
          style: 'currency',
          currency: 'NGN'
        }).format(amount);
        return res.replace("NGN", '');
      }
      return '0.00';
    },
    dynamic_data:{
        Subject: { required:1, show: 1, activate: 1, value: '', type: 'text', for: '' },
        Approval_Date: { required:1, show: 1, activate: 1, value: '', type: 'date', for: '' },
        Section_of_Work_Plan: { required:0, show: 1, activate: 1, value: '', type: 'text', for: '' },
        File_Name: { required:0, show: 1, activate: 1, value: '', type: 'text', for: '' },
        File_Number: { required:0, show: 1, activate: 1, value: '', type: 'text', for: '' },
        Page_Number: { required:0, show: 1, activate: 1, value: '', type: 'text', for: '' },
        Beneficiary: { required:1, show: 1, activate: 1, value: '', type: 'text', for: '' },
        Account_Number: { required:1, show: 1, activate: 1, value: 0, type: 'number', for: '' },
        Amount: { required:1, show: 1, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 },
        Payment_Date: { required:1, show: 1, activate: 1, value: '', type: 'date', for: '' },
        Trx_Charges: { required:0, show: 1, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 },
        'VAT_%': { required:0, show: 1, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 },
        'VAT_₦': { required:0, show: 0, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 },
        'Withholding_Tax_%': { required:0, show: 1, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 },
        'Withholding_Tax_₦': { required:0, show: 0, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 },
        'Stamp_Duty_%': { required:0, show: 1, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 },
        'Stamp_Duty_₦': { required:0, show: 0, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 },
        Gross_Amount: { required:1, show: 1, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 },
        Total_Taxes: { required:1, show: 1, activate: 1, value: 0, type: 'number', for: 'tax', amount: 0 }
    },
    // Add other global functions or variables here
  };
  