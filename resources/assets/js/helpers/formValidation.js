const FormValidation = {
  getInvalidFields: function(form, fields) {
    const invalidFields = [];

    fields.forEach(field => {
      const invalid = !this.isFieldValid(form, field);

      if (invalid) {
        invalidFields.push(field.name);
      }
    });

    return invalidFields;
  },

  isFieldValid: function(form, field) {
    if (field.type === 'text') {
      if (!this.isValidText(form, field)) {
        return false;
      }
    }

    if (field.type === 'email') {
      if (!this.isValidEmail(form, field.name)) {
        return false;
      }
    }

    if (field.type === 'phone') {
      if (!this.isValidPhone(form, field.name)) {
        return false;
      }
    }

    if (field.type === 'select') {
      if (!this.isValidSelect(form, field.name, field.values)) {
        return false;
      }
    }

    if (field.type === 'checkbox') {
      if (!this.isValidCheckbox(form, field.name)) {
        return false;
      }
    }

    return true;
  },

  isValidText: function(form, field) {
    const el = form.querySelector('[name="' + field.name + '"]');

    if (!el) {
      return false;
    }

    if (field.hasOwnProperty('length')) {
      if (el.value.trim().length < field.length.min) {
        return false;
      }
    }

    return el.value.trim().length != 0;
  },

  isValidEmail: function(form, fieldName) {
    const el = form.querySelector('[name="' + fieldName + '"]');
    const emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (!el) {
      return false;
    }

    if (el.value.trim().length === 0) {
      return false;
    }

    return emailRegex.test(el.value);
  },

  isValidPhone: function(form, fieldName) {
    const el = form.querySelector('[name="' + fieldName + '"]');
    const phoneRegex = /0[1-9]\d{8}|0[1-9]( |-)\d{8}|0[1-9]\d( |-)\d{7}|0[1-9]\d{2}( |-)\d{6}|(00|\+)[- \d]{10,}/;

    if (!el) {
      return false;
    }

    if (el.value.trim().length === 0) {
      return false;
    }

    return phoneRegex.test(el.value);
  },

  isValidSelect: function(form, fieldName, allowedValues) {
    const el = form.querySelector('[name="' + fieldName + '"]');

    if (!el) {
      return false;
    }

    if (!~allowedValues.indexOf(el.value)) {
      return false;
    }

    return true;
  },

  isValidCheckbox: function(form, fieldName) {
    const el = form.querySelector('[name="' + fieldName + '"]');

    if (!el) {
      return false
    };

    return el.checked;
  },

  setFormAsInvalid: function(form, invalidFields) {
    invalidFields.forEach(invalidField => {
      var field = form.querySelector('[name="' + invalidField + '"]');

      if (field.classList.contains('success')) {
        field.classList.remove('success');
      }

      field.classList.add('error');
    });
  },

  setFieldAsValid: function(field) {
    if (field.nodeName === 'SELECT') {
      this.setSelectAsValid(field);
    } else {
      this.setInputAsValid(field);
    }
  },

  setSelectAsValid: function(field) {
    var currentValueLength = field.options[ field.selectedIndex ].value.trim().length;

    if (!currentValueLength && field.classList.contains('success')) {
      field.classList.remove('success');
    } else if (currentValueLength) {
      field.classList.add('success');
    }
  },

  setInputAsValid: function(field) {
    if (field.value.trim().length || field.checked) {
      field.classList.add('success');
    } else if (field.classList.contains('success')) {
      field.classList.remove('success');
    }
  },

  resetInvalidField: function(form, fieldName) {
    var field = form.querySelector('[name="' + fieldName + '"]');
    field.classList.remove('error');
  }
};

export default FormValidation;
