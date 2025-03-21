function setCookie(name, value, daysToLive = 365, path = "/") {
    // Создаем дату истечения срока действия куки
    const date = new Date();
    date.setTime(date.getTime() + (daysToLive * 24 * 60 * 60 * 1000)); // Преобразуем дни в миллисекунды
    const expires = "expires=" + date.toUTCString();

    // Устанавливаем куки
    document.cookie = `${name}=${encodeURIComponent(value)}; ${expires}; path=${path}`;
}

setCookie("username", "John Doe", 30); // Установит куки на 30 дней
setCookie("theme", "dark", 365, "/settings"); // Установит куки на год с путем /settings

function getCookie(name) {
    // Разделяем все куки на массив
    const cookieArr = document.cookie.split(";");

    // Проходим по массиву куки
    for (let i = 0; i < cookieArr.length; i++) {
        const cookiePair = cookieArr[i].split("=");

        // Удаляем лишние пробелы и проверяем имя куки
        const cookieName = cookiePair[0].trim();
        if (cookieName === name) {
            return decodeURIComponent(cookiePair[1]); // Возвращаем значение куки
        }
    }

    // Если куки не найдено, возвращаем null
    return null;
}

const username = getCookie("username");
console.log(username); // "John Doe"

function deleteCookie(name, path = "/") {
    // Устанавливаем куки с прошедшей датой истечения
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=${path}`;
}

deleteCookie("username"); // Удалит куки "username"

// Устанавливаем куки
setCookie("username", "John Doe", 30);
setCookie("theme", "dark", 365);

// Читаем куки
console.log(getCookie("username")); // "John Doe"
console.log(getCookie("theme")); // "dark"

// Удаляем куки
deleteCookie("username");
console.log(getCookie("username")); // null