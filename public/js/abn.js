/*jslint indent: 2 */
$().ready(function () {
  var ABNControl = {
    permitted_key: function (e) {
      var numeric_keys, editing_keys;
      numeric_keys = [48, 49, 50, 51, 52, 53, 54, 55, 56, 57];
      editing_keys = [8, 13, 46, 37, 38, 40, 9, 0, 35, 36, 39, 32];
      numpad_keys = [96, 97, 98, 99, 100, 101, 102, 103, 104, 105];


      if (numeric_keys.indexOf(e.which) > -1) {
        return true;
      }

      if (editing_keys.indexOf(e.which) > -1) {
        return true;
      }

      if (numpad_keys.indexOf(e.which) > -1) {
        return true;
      }

      if (e.ctrlKey) {
        return true;
      }

      return false;
    },

    format_abn: function (value) {
      value = value.split(" ").join("");

      return [
        value.slice(0, 2),
        value.slice(2, 5),
        value.slice(5, 8),
        value.slice(8, 11)
      ].join(" ").trim();
    },

    validate_abn: function (value) {
      var total, weightings, new_value, i;
      //http://www.ato.gov.au/Business/Australian-business-number/In-detail/Introduction/Format-of-the-ABN/
      weightings = [10, 1, 3, 5, 7, 9, 11, 13, 15, 17, 19];
      new_value = value.split(" ").join("");

      if (new_value.length !== 11) {
        return false;
      }

      // Subtract 1 from the first (left) digit to give a new eleven digit number
      new_value = String(parseInt(new_value, 10) - 10000000000);

      // Multiply each of the digits in this new number by its weighting factor
      total = 0;
      for (i = 0; i < new_value.length; i++) {
        // Sum the resulting 11 products
        total += parseInt(new_value.charAt(i), 10) * weightings[i];
      }

      // Divide the total by 89, noting the remainder
      // If the remainder is zero the number is valid

      return total % 89 === 0;
    }
  };

  $.fn.bindABNControls = function () {
    this.keydown(function (e) {
      if (!ABNControl.permitted_key(e)) {
        e.preventDefault();
      }
    });

    // TODO Maintain cursor position?
    this.keypress(function (e) {
      var value = $(this).val().split(" ").join("");

      if (value.length >= 11) {
        e.preventDefault();
      }

      $(this).val(ABNControl.format_abn($(this).val()));
      this.setCustomValidity(
        ABNControl.validate_abn($(this).val()) ? "" : "Not a valid ABN"
      );
    });

    this.change(function () {
      $(this).val(ABNControl.format_abn($(this).val()));
      this.setCustomValidity(
        ABNControl.validate_abn($(this).val()) ? "" : "Not a valid ABN"
      );
    });

    this.each(function () {
      this.setCustomValidity(
        ABNControl.validate_abn($(this).val()) ? "" : "Not a valid ABN"
      );
    });
  };
});