[title = Website API]
[description = Website API provide a great way to get MusicBee release, download, addons detail and updates as well as addons search. The API is experimental and might contain unknown response/data.]


<p class="show_info">
API Version: 1.0 <br>
Request Type: GET <br>
Return Type: JSON <br>
API Key: <b>Currently no API key or registration is required. The API is fully open but it might change in future if too many request is slowing down the server.</b>
</p>

#### MusicBee beta, stable and patch release info

The following URL will return MusicBee stable, beta, and patch release info with version, download links and others.

```Request-URL

http://getmusicbee.com/api/1.0/?type=json&action=release-info
```
The URL will return json structured data

```json
{
  "stable":{
    "appname":"MusicBee 3",
    "version":"3.0.5805",
    "release_date":"May 30, 2016",
    "supported_os":"Windows 7\/8.1\/10",
    "download":{
      "available":"1",
      "installer":{
        "link1":"http:\/\/getmusicbee.com\/installer.exe",
        ...
      "portable":{
        "link1":"http:\/\/portable.getmusicbee.com\/installer.exe"}
    }
  },
  "beta":{
    ...
  },
  "patch":{
    ...
  }
}
```


----------

#### ADD-ON Data

Get add-on data such as description, download link, like & download count and others.


```Request-URL
http://getmusicbee.com/api/1.0/?type=json&action=addon-info&id=1
```

<p class="show_info warning">Parameter <code>id</code> is the id of the add-on.</p>


----------

#### ADD-ON list by User ID

List add-ons submitted by a user


```Request-URL
http://getmusicbee.com/api/1.0/?type=json&action=addon-list&authorid=1&limit=10
```

<p class="show_info warning">Parameter <code>authorid</code> is the id of the user/author.<br>
You need to define the <code>limit</code> of the result, if not defined the default will be 5, maximum limit is 20
</p>


----------


#### ADD-ON Search by Term

You can also search add-ons by providing a search query, category (optional), limit (optional), page (optional)


```Request-URL
http://getmusicbee.com/api/1.0/?type=json&action=addon-search&search=windows&page=1&limit=10
```
<p class="show_info warning">
Parameter <code>search</code> is the search query<br>
<code>page</code> is the current page no<br/>
<code>limit</code> is the result limit<br>
</p>

If you wan't full info about pagination like current page no, total page no etc, it is possible. The JSON response will contain the following:

```JSON
{
  "current_page":"1",
  "addon_data":{
    "result":[
      ....
    ]
  },
  "total_page":1,
  "page_url":"http:\/\/getmusicbee.com\/addons\/s\/?q=windows&type=",
  "prev_page_url":null,
  "next_page_url":null
}
```
You will get current page no, total page no, and the original URL of the page with only the search term as well as the link for the next and previous page.