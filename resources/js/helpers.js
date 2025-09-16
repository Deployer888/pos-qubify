import Vue from "vue";

var languageText = window.localStorage.getItem("app-languages");

export const trans = (title_key) => {
    const langObject = JSON.parse(languageText);
    return langObject[title_key];
};
// resources/js/helper.js

export function formatNumber(number) {
    console.log(window.appConfig);

    const locale = window.appConfig.decimal_separator || "en-US";
    const decimals = window.appConfig.decimal_precision || 2;

    console.log(locale);
    console.log(decimals);

    const localeSettings = {
        "en-US": { thousandsSeparator: ",", decimalSeparator: "." },
        "en-IN": { thousandsSeparator: ",", decimalSeparator: "." },
        "es-ES": { thousandsSeparator: ".", decimalSeparator: "," },
        "fr-FR": { thousandsSeparator: " ", decimalSeparator: "," },
        "it-CH": { thousandsSeparator: "’", decimalSeparator: "." },
        "bn-BD": { thousandsSeparator: ",", decimalSeparator: "." },
        "ar-SA": { thousandsSeparator: "٬", decimalSeparator: "٫" },
    };

    const { thousandsSeparator, decimalSeparator } = localeSettings[locale] || {
        thousandsSeparator: ",",
        decimalSeparator: ".",
    };

    // Ensure the input is a number
    const num = parseFloat(number);
    if (isNaN(num)) {
        console.warn("Invalid number format");
        return number; // or return a default value, e.g., "0"
    }

    // Convert number to a string and split into integer and decimal parts
    const parts = num.toFixed(decimals).split(".");

    // Format integer part with thousand separators
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);

    // Join integer and decimal parts with the appropriate decimal separator
    return parts.join(decimalSeparator);
}
