callStartRequest - запрос от АТС при соединении
    HTTP:
        Method: POST
        Host/port - Хост проекта
        Path - Путь приложения callTracking
    Request Parameters:
        id: string, любой
        int: string, исходящий номер клиента
        ext: string, номер АТС (виртуальный)

callEndRequest - запрос от АТС при окончании разговора
    HTTP:
        Method: POST
        Host/port - Хост проекта
        Path - Путь приложения callTracking
    Request Parameters:
        id: string, тот же, что и в callStartRequest
        duration: integer, любой
        mp3link: string, ссылка на любой общедоступный mp3



