const RENEWAL_STAGES = {
  "1": "Client Quoted",
  "2" : "Client Invoiced",
  "3" : "Client Paid",
  "4":  "Payment To Liquor Board",
  "5": "Renewal Issued",
  "6": "Renewal Delivered"
  }

  const TRANSFER_STAGES = {
    "1" : "Client Quoted",
    "2" : "Client Invoiced",
    "3" : "Client Paid",
    "4" : "Prepare Transfer Application",
    "5" : "Payment To The Liquor Board",
    "6" : "Scanned Application",
    "7" : "Application Logded",
    "8" : "Activation Fee Paid",
    "9" : "Transfer Issued",
    "10" : "Transfer Delivered"
  }

  const NOMINATION_STAGES = {
    "1" : "Client Quoted",
    "2" : "Client Invoiced",
    "3" : "Client Paid",
    "4" : "Payment to the Liquor Board",
    "5" : "Select nominees",
    "6" : "Prepare Nomination Application", 
    "7" : "Scanned Application",
    "8" : "Nomination Lodged", 
    "9" : "Nomination Issued", 
    "10" : "Nomination Delivered" 
  }

  const ALTERATION_STAGES = {
      "1" : "Client Quoted",
      "2" : "Client Invoiced",
      "3" : "Client Paid", 
      "4" : "Prepare Alterations Application",
      "5" : "Payment to the Liquor Board",
      "6" : "Alterations Lodged",
      "7" : "Alterations Certificate Issued",
      "8" : "Alterations Delivered",

  }

  const TEMP_LICENCE_STAGES = {
   "1" : "Client Quoted",
   "2" : "Client Invoiced",
   "3" : "Client Paid",
   "4" : "Collate Temporary Licence Documents ",
   "5" : "Payment To The Liquor Board ",
   "6" : "Scanned Application",
   "7" : "Temporary Licence Lodged",  
   "8" : "Temporary Licence Issued", 
   "9" : "Temporary Licence Delivered", 
  }

  const NEW_APP_STAGES = {

    "1" : "Client Quoted",
    "2" : "Deposit Invoice",
    "3": "Deposit Paid",
    "4": "Payment to the Liquor Board",
    "5": "Prepare New Application",
    "6": "Scanned Application",
    "7": "Application Lodged",
    "8": "Initial Inspection",
    "9": "Liquor Board Requests",
    "10": "Final Inspection",
    "11": "Activation Fee Requested",
    "12": "Client Finalisation Invoice",
    "13": "Client Paid",
    "14": "Activation Fee Paid",
    "15": "Licence Issued",
    "16": "Licence Delivered",

}

const MONTHS = {
  "1": "January",
  "2" : "February",
  "3" : "March",
  "4":   "April",
  "5": "May",
  "6": "June",
  "7": "July",
  "8": "August",
  "9": "September",
  "10": "October",
  "11": "November",
  "12": "December",
}
export default{
  getRenewalStages() {
    return RENEWAL_STAGES;
  },

  getNewAppStages() {
    return NEW_APP_STAGES;
  },

  getTransferStages() {
    return TRANSFER_STAGES;
  },

  getNominationStages(){
    return NOMINATION_STAGES;
  },

  getAlterationStages(){
    return ALTERATION_STAGES;
  },

  getTempLicenceStages(){
    return TEMP_LICENCE_STAGES;
  },

  getMonths(){
    return MONTHS;
  },

}
 