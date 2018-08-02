<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="author" content="indins.ru">
        <title>{METADATA_TITLE}</title>
        <meta name="description" content="{METADATA_DESCRIPTION}" />
        <meta name="keywords" content="{METADATA_KEYWORDS}" />
        <meta property="og:title" content="{METADATA_TITLE}" />
        <meta property="og:description" content="{METADATA_DESCRIPTION}" />
        <meta property="og:locate" content="en_US" />
        <meta property="og:type" content="website" />
        <meta property="og:tag" content="{METADATA_KEYWORDS}" />
        <meta property="og:url" content="{METADATA_PAGE_URL}" />
        <meta property="og:image" content="{METADATA_IMAGE_URI}" />

        {CSS_TOP}
        {JS_TOP}
    </head>
    <body>
        <nav class="menu">
            {MENU}
        </nav>
        <section class="content-full-page">
            <h1 class="content-full-title">{DATA_NAME}</h1>
            <div class="content-full-body">
                {DATA_CONTENT}
            </div>
        </section>
        {JS_BOTTOM}
        {CSS_BOTTOM}
    </body>
</html>