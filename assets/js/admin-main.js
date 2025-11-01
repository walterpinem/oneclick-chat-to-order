// Shortcode Generator
function generateWAshortcode() {
	// Check if required elements exist before proceeding
	var selectedNumberEl = document.getElementById("selected_wa_number");
	var buttonTextEl = document.getElementById("WAbuttonText");
	var customMessageEl = document.getElementById("WAcustomMessage");
	var newTabEl = document.getElementById("WAnewTab");
	var outputEl = document.getElementById("generatedShortcode");

	if (!selectedNumberEl || !buttonTextEl || !customMessageEl || !newTabEl || !outputEl) {
		return; // Silently return if elements don't exist
	}

	var Vselected_wa_number = selectedNumberEl.value;
	var VWAbuttonText = buttonTextEl.value;
	var VWAcustomMessage = customMessageEl.value;
	var VWAnewTab = newTabEl.value;
	var generatedWAbuttonData = '[waorder phone="'+Vselected_wa_number+'" button="'+VWAbuttonText+'" message="'+VWAcustomMessage+'" target="'+VWAnewTab+'"]';
	outputEl.innerHTML = generatedWAbuttonData;
}

jQuery(document).ready(function ($) {
  // Function to toggle the full-width option visibility
  function toggleFullWidthOption() {
      var $buttonPosition = $('#wa_order_single_product_button_position');
      var $fullwidthContainer = $('#force_fullwidth_container');
      var $fullwidthCheckbox = $('#wa_order_single_force_fullwidth');

      // Only proceed if the elements exist
      if ($buttonPosition.length === 0 || $fullwidthContainer.length === 0) {
          return;
      }

      var buttonPosition = $buttonPosition.val();

      if (buttonPosition === 'after_atc') {
          $fullwidthContainer.hide(); // Hide checkbox
          if ($fullwidthCheckbox.length > 0) {
              $fullwidthCheckbox.prop('checked', false).val('no'); // Reset to 'No'
          }
      } else {
          $fullwidthContainer.show(); // Show checkbox
      }
  }

  // Run on page load
  toggleFullWidthOption();

  // Attach event listener to dropdown change
  $('#wa_order_single_product_button_position').change(function () {
      toggleFullWidthOption();
  });
});

// Global variables for Single Product Shortcode Generator
var SingleWAShortcodeElements = {};

// Global function to generate single WA shortcode (accessible to HTML onchange attributes)
function generateSingleWAshortcode() {
    // Check if elements are initialized
    if (!SingleWAShortcodeElements.initialized) {
        return;
    }

    var productSelect = SingleWAShortcodeElements.productSelect;
    var productIdField = SingleWAShortcodeElements.productIdField;
    var buttonText = SingleWAShortcodeElements.buttonText;
    var customMessage = SingleWAShortcodeElements.customMessage;
    var shortcodeOutput = SingleWAShortcodeElements.shortcodeOutput;
    var waNumberSelect = SingleWAShortcodeElements.waNumberSelect;
    var buttonForceFullwidth = SingleWAShortcodeElements.buttonForceFullwidth;

    var productValue = productSelect.value;
    var productId = productIdField.value;
    var buttonTextValue = buttonText.value.trim();
    var customMessageValue = customMessage.value.trim();
    var selectedWaNumber = waNumberSelect.value;
    var isFullwidth = buttonForceFullwidth.value;

    // Default shortcode
    var shortcode = '[oneclick single="true"';

    // Add phone attribute (WhatsApp number)
    if (selectedWaNumber !== '') {
        shortcode += ' phone="' + selectedWaNumber + '"';
    }

    // Handle product attribute
    if (productValue === 'current') {
        shortcode += ' product="current"';
    } else if (productValue === 'product_id' && productId !== '') {
        shortcode += ' product="' + productId + '"';
    }

    // Handle text attribute
    if (buttonTextValue !== '') {
        shortcode += ' text="' + buttonTextValue + '"';
    }

    // Handle message attribute
    if (customMessageValue !== '') {
        shortcode += ' message="' + customMessageValue + '"';
    }

    // Force fullwidth
    if (isFullwidth !== '') {
        shortcode += ' fullwidth="' + isFullwidth + '"';
    }

    // Close the shortcode
    shortcode += ']';

    // Output the generated shortcode
    shortcodeOutput.value = shortcode;
}

// Single Product Shortcode Generator Initialization
document.addEventListener('DOMContentLoaded', function() {
    // Select DOM elements
    var productSelect = document.getElementById('SingleWAWhichPage');
    var productIdField = document.getElementById('SingleWAProductID');
    var buttonText = document.getElementById('SingleWAbuttonText');
    var customMessage = document.getElementById('SingleWAcustomMessage');
    var shortcodeOutput = document.getElementById('generatedSingleWAShortcode');
    var waNumberSelect = document.getElementById('selected_wa_number');
    var buttonForceFullwidth = document.getElementById('SingleWAFullwidth');

    // Check if all required elements exist - only initialize if we're on the shortcode page
    if (!productSelect || !productIdField || !buttonText || !customMessage || !shortcodeOutput || !waNumberSelect || !buttonForceFullwidth) {
        // Silently return if elements don't exist (we're not on the shortcode page)
        return;
    }

    // Store elements globally
    SingleWAShortcodeElements = {
        productSelect: productSelect,
        productIdField: productIdField,
        buttonText: buttonText,
        customMessage: customMessage,
        shortcodeOutput: shortcodeOutput,
        waNumberSelect: waNumberSelect,
        buttonForceFullwidth: buttonForceFullwidth,
        initialized: true
    };

    // Function to hide an element
    function hideElement(element) {
        if (element && element.parentElement && element.parentElement.parentElement) {
            element.parentElement.parentElement.style.display = 'none';
        }
    }

    // Function to show an element
    function showElement(element) {
        if (element && element.parentElement && element.parentElement.parentElement) {
            element.parentElement.parentElement.style.display = '';
        }
    }

    // Hide Product ID field initially
    hideElement(productIdField);

    // Event listeners for changes
    productSelect.addEventListener('change', function() {
        if (productSelect.value === 'product_id') {
            showElement(productIdField);
        } else {
            hideElement(productIdField);
            productIdField.value = ''; // Clear the Product ID field
        }
        generateSingleWAshortcode();
    });

    productIdField.addEventListener('input', generateSingleWAshortcode);
    buttonText.addEventListener('input', generateSingleWAshortcode);
    customMessage.addEventListener('input', generateSingleWAshortcode);
    waNumberSelect.addEventListener('change', generateSingleWAshortcode);
    buttonForceFullwidth.addEventListener('change', generateSingleWAshortcode);

    // Initial generation of shortcode
    generateSingleWAshortcode();
});