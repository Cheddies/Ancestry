

//Register TGN namespace
if (typeof TGN == "undefined") { var TGN = {}; }
var _YDom = YAHOO.util.Dom;

TGN.namespace = function() {
    var a=arguments, o=null, i, j, d;
    for (i=0; i<a.length; i++) {
        d=a[i].split(".");
        o=TGN;
        // TGN is implied, so it is ignored if it is included
        for (j=(d[0] == "TGN") ? 1 : 0; j<d.length; j++) {
            o[d[j]]=o[d[j]] || {};
            o=o[d[j]];
        }
    }
    return o;
};

//Register TGN.Ancestry namespace
var $Anc = TGN.namespace("Ancestry");

$Anc.Partner = {};
//Add domains to the "$Anc.Partner" namespace as NEEDED!!!
$Anc.Partner.cacheDomain = "http://c.ancestry.com";

$Anc.ShowHide = function(id) {
    var e = document.getElementById(id);
    if (e != null) {
        if (e.style.display == "block") {
            e.style.display = "none";
        }
        else {
            e.style.display = "block";
        }
    }
};
//Publish exceptions to server, must have handler in place on server
$Anc.PublishException = function(feature, method, err, jsFile) {
    var url = "/JavaScriptException.ashx?f=" + escape(feature) + "&m=" + escape(method) + "&e=" + escape(err) + "&js=" + escape(jsFile) + "&u=" + escape(document.location.href);
    YAHOO.util.Get.script(url, {});
    if (typeof console != "undefined")
        console.error("JavaScript Error: Feature: " + feature + " Method: " + method + " Err: " + err + " JsFile: " + jsFile);
    if (document.location.href.indexOf("jsdebug=alert") > 1)
        alert("JavaScript Error: \nFeature: " + feature + " \nMethod: " + method + " \nErr: " + err + " \nJsFile: " + jsFile);
    if (document.location.href.indexOf("jsdebug=break") > 1)
        throw "$Anc.PublishException: jsdebug=break";
};

$Anc.loaded = new Array();
$Anc.depend = function() { };


$Anc.load = function(config) {
    if (config.type != undefined && config.type.length > 0) {
        if ($Anc.isLoaded(config.type)) {
            $Anc.loadLibs(config);
        }
        else {
            var loader = new YAHOO.util.YUILoader({
                require: "dependency",
                onSuccess: function() {
                    $Anc.loadLibs(this.data);
                },
                onFailure: function(o) { if (this.data.onFailure != undefined) this.data.onFailure(o.msg); },
                onTimeout: function(o) {
                    if (this.data.onTimeout != undefined)
                        this.data.onTimeout(o.msg);
                    else if (this.data.failure != undefined)
                        this.data.onFailure(o.msg);
                },
                data: config,
                timeout: config.timeout != undefined ? config.timeout : 10000
            });
            var combineTGN = config.combineTGN != undefined && !config.combineTGN ? "&cmbTGN=false" : "";
            var combineYUI = config.combineYUI != undefined && !config.combineYUI ? "&cmbYUI=false" : "";
            loader.addModule({ name: "dependency", type: "js", fullpath: "http://c.ancestry.com/dependency.jsx?v=1&type=" + config.type + combineTGN + combineYUI });
            loader.insert();
        }
    }
    else if (config.libs != undefined && config.libs.length > 0) {
        var dependNames = new Array();
        var i;
        for (i = 0; i < config.libs.length; i++) {
            if (!$Anc.isLoaded(config.libs[i].name))
                dependNames.push(config.libs[i].name);
        }
        if (dependNames.length > 0) {
            var loader = new YAHOO.util.YUILoader({
                require: dependNames,
                onSuccess: function() {
                    var i;
                    for (i = 0; i < this.data.libs.length; i++) {
                        $Anc.loaded.push(this.data.libs[i].name);
                    }
                    if (this.data.onSuccess != undefined)
                        this.data.onSuccess(this.data);
                },
                onFailure: function(o) { if (this.data.onFailure != undefined) this.data.onFailure(o.msg); },
                onTimeout: function(o) {
                    if (this.data.onTimeout != undefined)
                        this.data.onTimeout(o.msg);
                    else if (this.data.failure != undefined)
                        this.data.onFailure(o.msg);
                },
                data: config,
                timeout: config.timeout != undefined ? config.timeout : 10000
            });
            for (i = 0; i < config.libs.length; i++) {
                loader.addModule(config.libs[i]);
            }
            loader.insert();
        }
        else {
            if (config.onSuccess != undefined)
                config.onSuccess(config);
        }
    }
    else {
        if (config.onFailure != undefined)
            config.onFailure("no type or libs defined");
    }
};

$Anc.loadLibs = function(config) {
    var type = config.type.toLowerCase();
    if ($Anc.depend[type].names.length > 0) {
        var dependNames = new Array();
        var dependLibs = $Anc.depend[type].libs;
        //look to see if any of the required libs have already been loaded, ignore the following names:
		if (!$Anc.isLoaded(type)) {
			var i;
			var name;
			for (i = 0; i < $Anc.depend[type].names.length; i++) {
				name = $Anc.depend[type].names[i];
				if (!$Anc.isLoaded(name))
					dependNames.push(name);
			}
		}
        //look to see if any dependencies have been defined in the config object
        if (config.libs != undefined && config.libs.length > 0) {
            for (i = 0; i < config.libs.length; i++) {
                if (!$Anc.isLoaded(config.libs[i].name)) {
                    dependNames.push(config.libs[i].name);
                    dependLibs.push(config.libs[i]);
                }
            }
        }
        if (dependNames.length > 0) {
            config.dependNames = dependNames;
            var loader = new YAHOO.util.YUILoader({
                require: dependNames,
                onSuccess: function() {
                    var i;
                    //if all the dependencies loaded then it is safe to say that the whole type has been loaded
                    $Anc.loaded.push(this.data.type);
                    //Don't store the fact that the following names have been loaded
                    //"yuiLibs", "yuiAssets", "tgnLibs", "tgnAssets". The reason for this is these names represent combos
                    //and could represent different files depending on the type
                    var name;
                    for (i = 0; i < this.data.dependNames.length; i++) {
                        name = this.data.dependNames[i];
                        if (name != "yuiLibs" && name != "yuiAssets" && name != "tgnLibs" && name != "tgnAssets")
                            $Anc.loaded.push(name);
                    }
                    if (this.data.onSuccess != undefined)
                        this.data.onSuccess(this.data);
                },
                onFailure: function(o) { if (this.data.onFailure != undefined) this.data.onFailure(o.msg); },
                onTimeout: function(o) {
                    if (this.data.onTimeout != undefined)
                        this.data.onTimeout(o.msg);
                    else if (this.data.failure != undefined)
                        this.data.onFailure(o.msg);
                },
                data: config,
                timeout: config.timeout != undefined ? config.timeout : 10000
            });
            for (i = 0; i < dependLibs.length; i++) {
                loader.addModule(dependLibs[i]);
            }
            loader.insert();
        }
        else {
            if(config.onSuccess != undefined)
                config.onSuccess(config);
        }
    }
    else {
        if (config.onFailure != undefined)
            config.onFailure("no dependencies defined");
    }
};

$Anc.isLoaded = function(name) {
    var i;
    name = name.toLowerCase();
    for (i = 0; i < $Anc.loaded.length; i++) {
        if ($Anc.loaded[i].toLowerCase() == name)
            return true;
    }
    return false;
};