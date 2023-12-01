function updateDonationValue() {
    var rankDropdown = document.getElementById("rank-dropdown");
    var donationInput = document.getElementById("sponsordonation");

    // Get the selected value from the dropdown
    var selectedRank = rankDropdown.options[rankDropdown.selectedIndex].value;

    // Update the donation input based on the selected rank
    switch (selectedRank) {
      case "goud":
        donationInput.value = "75.00"; 
        break;
      case "zilver":
        donationInput.value = "50.00"; 
        break;
      case "brons":
        donationInput.value = "25.00"; 
        break;
      default:
        donationInput.value = "75.00"; 
        break;
    }
}