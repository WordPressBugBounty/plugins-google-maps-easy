jQuery(document).ready(function () {
  // Onboarding "don't have a key yet?" help toggle - bound here (not inline
  // onclick) because wp_kses strips onclick= from admin output.
  jQuery('#gmpOnboardingApiKeyHelpToggle').on('click', function () {
    jQuery('#gmpOnboardingApiKeyHelp').toggle();
    return false;
  });
  jQuery('#gmpSettingsSaveBtn').click(function () {
    _gmpSaveMainOpts();
    return false;
  });
  jQuery('#gmpSettingsForm').submit(function () {
    var sendParams = {
      btn: jQuery('#gmpSettingsSaveBtn'),
    };
    // Onboarding: after the API key is saved, send the user straight to creating their first map
    if (typeof gmpOnboardingRedirect != 'undefined' && gmpOnboardingRedirect) {
      sendParams.onSuccess = function (res) {
        if (res && !res.error) {
          var apiKeyVal = jQuery.trim(jQuery('[data-optkey="user_api_key"]').val());
          if (apiKeyVal && typeof gmpOnboardingAddMapUrl != 'undefined') {
            window.location.href = '' + gmpOnboardingAddMapUrl;
          }
        }
      };
    }
    jQuery(this).sendFormGmp(sendParams);
    return false;
  });
});
function _gmpSaveMainOpts() {
  jQuery('#gmpSettingsForm').submit();
}
