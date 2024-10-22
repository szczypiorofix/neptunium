<?php

namespace Neptunium\Core\ModelClasses;

enum RenderParamsEnum: string {
    case TEMPLATE_FILE_NAME = "templateFileName";
    case TEMPLATE_NAME      = "templateName";
    case QUERY_DATA         = "queryData";
    case SESSION_DATA       = "sessionData";
    case NAVIGATION_DATA    = "navigationData";
    case NOTIFICATIONS      = "notifications";
    case LOGIN_DATA         = "loginData";
    case BASE_URL           = "baseUrl";
    case APP_VER            = "appVer";
    case DEBUG_INFO_DATA    = "debugInfoData";
    case DEBUG_WARNING_DATA = "debugWarningData";
    case DEBUG_ERROR_DATA   = "debugErrorData";
}
