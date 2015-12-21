        var deviceIphone = "iphone";
        var deviceIpod = "ipod";

        //Initialize our user agent string to lower case.
        var uagent = navigator.userAgent.toLowerCase();

        //**************************
        // Detects if the current device is an iPhone.
        function DetectIphone()
        {
           if (uagent.search(deviceIphone) > -1)
              return true;
           else
              return false;
        }

        //**************************
        // Detects if the current device is an iPod Touch.
        function DetectIpod()
        {
           if (uagent.search(deviceIpod) > -1)
              return true;
           else
              return false;
        }

        //**************************
        // Detects if the current device is an iPhone or iPod Touch.
        function DetectIphoneOrIpod()
        {
            if (DetectIphone())
               return true;
            else if (DetectIpod())
               return true;
            else
               return false;
        }
        
        var deviceS60 = "series60";
        var deviceSymbian = "symbian";
        var engineWebKit = "webkit";

        //**************************
        // Detects if the current browser is the S60 Open Source Browser.
        // Screen out older devices and the old WML browser.
        function DetectS60OssBrowser()
        {
           if (uagent.search(engineWebKit) > -1)
           {
             if ((uagent.search(deviceS60) > -1 || 
                  uagent.search(deviceSymbian) > -1))
                return true;
             else
                return false;
           }
           else
              return false;
        }

        var deviceAndroid = "android";

        //**************************
        // Detects if the current device is an Android OS-based device.
        function DetectAndroid()
        {
           if (uagent.search(deviceAndroid) > -1)
              return true;
           else
              return false;
        }

        //**************************
        // Detects if the current device is an Android OS-based device and
        //   the browser is based on WebKit.
        function DetectAndroidWebKit()
        {
           if (DetectAndroid())
           {
             if (DetectWebkit())
                return true;
             else
                return false;
           }
           else
              return false;
        }
        
        var deviceWinMob = "windows ce";

        //**************************
        // Detects if the current browser is a Windows Mobile device.
        function DetectWindowsMobile()
        {
           if (uagent.search(deviceWinMob) > -1)
              return true;
           else
              return false;
        }

        var deviceBB = "blackberry";

        //**************************
        // Detects if the current browser is a BlackBerry of some sort.
        function DetectBlackBerry()
        {
           if (uagent.search(deviceBB) > -1)
              return true;
           else
              return false;
        }
        
        var devicePalm = "palm";

        //**************************
        // Detects if the current browser is on a PalmOS device.
        function DetectPalmOS()
        {
           if (uagent.search(devicePalm) > -1)
              return true;
           else
              return false;
        }
