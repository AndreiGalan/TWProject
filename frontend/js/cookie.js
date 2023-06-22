export function getCookie(cookieName) {
    var cookies = document.cookie.split(";"); // Split the cookies into an array

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim(); // Remove whitespace around the cookie

        // Check if the cookie starts with the specified name
        if (cookie.startsWith(cookieName + "=")) {
            var cookieValue = cookie.substring(cookieName.length + 1); // Extract the value after the equals sign
            return decodeURIComponent(cookieValue); // Decode the cookie value and return it
        }
    }

    return null; // Return null if the cookie is not found
}

export function deleteCookie(name) {
    document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}
