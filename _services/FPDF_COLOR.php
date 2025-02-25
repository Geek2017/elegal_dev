<?php 

namespace Services;

class FPDF_COLOR
{
    /**
	 * Set result to hex mode.
	 *
	 * @var boolean
	 */
	protected $isHex = false;

	/**
	* @var Array of Colors
	*/
	private $colors = [
		"crimson"              =>[220,20,60],
		"lightpink"            =>[255,182,193],
		"lightpink1"           =>[255,174,185],
		"lightpink2"           =>[238,162,173],
		"lightpink3"           =>[205,140,149],
		"lightpink4"           =>[139,95,101],
		"pink"                 =>[255,192,203],
		"pink1"                =>[255,181,197],
		"pink2"                =>[238,169,184],
		"pink3"                =>[205,145,158],
		"pink4"                =>[139,99,108],
		"palevioletred"        =>[219,112,147],
		"palevioletred1"       =>[255,130,171],
		"palevioletred2"       =>[238,121,159],
		"palevioletred3"       =>[205,104,137],
		"palevioletred4"       =>[139,71,93],
		"lavenderblush1"       =>[255,240,245],
		"lavenderblush2"       =>[238,224,229],
		"lavenderblush3"       =>[205,193,197],
		"lavenderblush4"       =>[139,131,134],
		"violetred1"           =>[255,62,150],
		"violetred2"           =>[238,58,140],
		"violetred3"           =>[205,50,120],
		"violetred4"           =>[139,34,82],
		"hotpink"              =>[255,105,180],
		"hotpink1"             =>[255,110,180],
		"hotpink2"             =>[238,106,167],
		"hotpink3"             =>[205,96,144],
		"hotpink4"             =>[139,58,98],
		"raspberry"            =>[135,38,87],
		"deeppink1"            =>[255,20,147],
		"deeppink2"            =>[238,18,137],
		"deeppink3"            =>[205,16,118],
		"deeppink4"            =>[139,10,80],
		"maroon1"              =>[255,52,179],
		"maroon2"              =>[238,48,167],
		"maroon3"              =>[205,41,144],
		"maroon4"              =>[139,28,98],
		"mediumvioletred"      =>[199,21,133],
		"violetred"            =>[208,32,144],
		"orchid"               =>[218,112,214],
		"orchid1"              =>[255,131,250],
		"orchid2"              =>[238,122,233],
		"orchid3"              =>[205,105,201],
		"orchid4"              =>[139,71,137],
		"thistle"              =>[216,191,216],
		"thistle1"             =>[255,225,255],
		"thistle2"             =>[238,210,238],
		"thistle3"             =>[205,181,205],
		"thistle4"             =>[139,123,139],
		"plum1"                =>[255,187,255],
		"plum2"                =>[238,174,238],
		"plum3"                =>[205,150,205],
		"plum4"                =>[139,102,139],
		"plum"                 =>[221,160,221],
		"violet"               =>[238,130,238],
		"magenta"              =>[255,0,255],
		"magenta2"             =>[238,0,238],
		"magenta3"             =>[205,0,205],
		"magenta4"             =>[139,0,139],
		"purple"               =>[128,0,128],
		"mediumorchid"         =>[186,85,211],
		"mediumorchid1"        =>[224,102,255],
		"mediumorchid2"        =>[209,95,238],
		"mediumorchid3"        =>[180,82,205],
		"mediumorchid4"        =>[122,55,139],
		"darkviolet"           =>[148,0,211],
		"darkorchid"           =>[153,50,204],
		"darkorchid1"          =>[191,62,255],
		"darkorchid2"          =>[178,58,238],
		"darkorchid3"          =>[154,50,205],
		"darkorchid4"          =>[104,34,139],
		"indigo"               =>[75,0,130],
		"blueviolet"           =>[138,43,226],
		"purple1"              =>[155,48,255],
		"purple2"              =>[145,44,238],
		"purple3"              =>[125,38,205],
		"purple4"              =>[85,26,139],
		"mediumpurple"         =>[147,112,219],
		"mediumpurple1"        =>[171,130,255],
		"mediumpurple2"        =>[159,121,238],
		"mediumpurple3"        =>[137,104,205],
		"mediumpurple4"        =>[93,71,139],
		"darkslateblue"        =>[72,61,139],
		"lightslateblue"       =>[132,112,255],
		"mediumslateblue"      =>[123,104,238],
		"slateblue"            =>[106,90,205],
		"slateblue1"           =>[131,111,255],
		"slateblue2"           =>[122,103,238],
		"slateblue3"           =>[105,89,205],
		"slateblue4"           =>[71,60,139],
		"ghostwhite"           =>[248,248,255],
		"lavender"             =>[230,230,250],
		"blue"                 =>[0,0,255],
		"blue2"                =>[0,0,238],
		"blue3"                =>[0,0,205],
		"blue4"                =>[0,0,139],
		"navy"                 =>[0,0,128],
		"midnightblue"         =>[25,25,112],
		"cobalt"               =>[61,89,171],
		"royalblue"            =>[65,105,225],
		"royalblue1"           =>[72,118,255],
		"royalblue2"           =>[67,110,238],
		"royalblue3"           =>[58,95,205],
		"royalblue4"           =>[39,64,139],
		"cornflowerblue"       =>[100,149,237],
		"lightsteelblue"       =>[176,196,222],
		"lightsteelblue1"      =>[202,225,255],
		"lightsteelblue2"      =>[188,210,238],
		"lightsteelblue3"      =>[162,181,205],
		"lightsteelblue4"      =>[110,123,139],
		"lightslategray"       =>[119,136,153],
		"slategray"            =>[112,128,144],
		"slategray1"           =>[198,226,255],
		"slategray2"           =>[185,211,238],
		"slategray3"           =>[159,182,205],
		"slategray4"           =>[108,123,139],
		"dodgerblue1"          =>[30,144,255],
		"dodgerblue2"          =>[28,134,238],
		"dodgerblue3"          =>[24,116,205],
		"dodgerblue4"          =>[16,78,139],
		"aliceblue"            =>[240,248,255],
		"steelblue"            =>[70,130,180],
		"steelblue1"           =>[99,184,255],
		"steelblue2"           =>[92,172,238],
		"steelblue3"           =>[79,148,205],
		"steelblue4"           =>[54,100,139],
		"lightskyblue"         =>[135,206,250],
		"lightskyblue1"        =>[176,226,255],
		"lightskyblue2"        =>[164,211,238],
		"lightskyblue3"        =>[141,182,205],
		"lightskyblue4"        =>[96,123,139],
		"skyblue1"             =>[135,206,255],
		"skyblue2"             =>[126,192,238],
		"skyblue3"             =>[108,166,205],
		"skyblue4"             =>[74,112,139],
		"skyblue"              =>[135,206,235],
		"deepskyblue1"         =>[0,191,255],
		"deepskyblue2"         =>[0,178,238],
		"deepskyblue3"         =>[0,154,205],
		"deepskyblue4"         =>[0,104,139],
		"peacock"              =>[51,161,201],
		"lightblue"            =>[173,216,230],
		"lightblue1"           =>[191,239,255],
		"lightblue2"           =>[178,223,238],
		"lightblue3"           =>[154,192,205],
		"lightblue4"           =>[104,131,139],
		"powderblue"           =>[176,224,230],
		"cadetblue1"           =>[152,245,255],
		"cadetblue2"           =>[142,229,238],
		"cadetblue3"           =>[122,197,205],
		"cadetblue4"           =>[83,134,139],
		"turquoise1"           =>[0,245,255],
		"turquoise2"           =>[0,229,238],
		"turquoise3"           =>[0,197,205],
		"turquoise4"           =>[0,134,139],
		"cadetblue"            =>[95,158,160],
		"darkturquoise"        =>[0,206,209],
		"azure1"               =>[240,255,255],
		"azure2"               =>[224,238,238],
		"azure3"               =>[193,205,205],
		"azure4"               =>[131,139,139],
		"lightcyan1"           =>[224,255,255],
		"lightcyan2"           =>[209,238,238],
		"lightcyan3"           =>[180,205,205],
		"lightcyan4"           =>[122,139,139],
		"paleturquoise1"       =>[187,255,255],
		"paleturquoise2"       =>[174,238,238],
		"paleturquoise3"       =>[150,205,205],
		"paleturquoise4"       =>[102,139,139],
		"darkslategray"        =>[47,79,79],
		"darkslategray1"       =>[151,255,255],
		"darkslategray2"       =>[141,238,238],
		"darkslategray3"       =>[121,205,205],
		"darkslategray4"       =>[82,139,139],
		"cyan/aqua"            =>[0,255,255],
		"cyan2"                =>[0,238,238],
		"cyan3"                =>[0,205,205],
		"cyan4"                =>[0,139,139],
		"teal"                 =>[0,128,128],
		"mediumturquoise"      =>[72,209,204],
		"lightseagreen"        =>[32,178,170],
		"manganeseblue"        =>[3,168,158],
		"turquoise"            =>[64,224,208],
		"coldgrey"             =>[128,138,135],
		"turquoiseblue"        =>[0,199,140],
		"aquamarine1"          =>[127,255,212],
		"aquamarine2"          =>[118,238,198],
		"aquamarine3"          =>[102,205,170],
		"aquamarine4"          =>[69,139,116],
		"mediumspringgreen"    =>[0,250,154],
		"mintcream"            =>[245,255,250],
		"springgreen"          =>[0,255,127],
		"springgreen1"         =>[0,238,118],
		"springgreen2"         =>[0,205,102],
		"springgreen3"         =>[0,139,69],
		"mediumseagreen"       =>[60,179,113],
		"seagreen1"            =>[84,255,159],
		"seagreen2"            =>[78,238,148],
		"seagreen3"            =>[67,205,128],
		"seagreen4"            =>[46,139,87],
		"emeraldgreen"         =>[0,201,87],
		"mint"                 =>[189,252,201],
		"cobaltgreen"          =>[61,145,64],
		"honeydew1"            =>[240,255,240],
		"honeydew2"            =>[224,238,224],
		"honeydew3"            =>[193,205,193],
		"honeydew4"            =>[131,139,131],
		"darkseagreen"         =>[143,188,143],
		"darkseagreen1"        =>[193,255,193],
		"darkseagreen2"        =>[180,238,180],
		"darkseagreen3"        =>[155,205,155],
		"darkseagreen4"        =>[105,139,105],
		"palegreen"            =>[152,251,152],
		"palegreen1"           =>[154,255,154],
		"palegreen2"           =>[144,238,144],
		"palegreen3"           =>[124,205,124],
		"palegreen4"           =>[84,139,84],
		"limegreen"            =>[50,205,50],
		"forestgreen"          =>[34,139,34],
		"green1"               =>[0,255,0],
		"green2"               =>[0,238,0],
		"green3"               =>[0,205,0],
		"green4"               =>[0,139,0],
		"green"                =>[0,128,0],
		"darkgreen"            =>[0,100,0],
		"sapgreen"             =>[48,128,20],
		"lawngreen"            =>[124,252,0],
		"chartreuse1"          =>[127,255,0],
		"chartreuse2"          =>[118,238,0],
		"chartreuse3"          =>[102,205,0],
		"chartreuse4"          =>[69,139,0],
		"greenyellow"          =>[173,255,47],
		"darkolivegreen1"      =>[202,255,112],
		"darkolivegreen2"      =>[188,238,104],
		"darkolivegreen3"      =>[162,205,90],
		"darkolivegreen4"      =>[110,139,61],
		"darkolivegreen"       =>[85,107,47],
		"olivedrab"            =>[107,142,35],
		"olivedrab1"           =>[192,255,62],
		"olivedrab2"           =>[179,238,58],
		"olivedrab3"           =>[154,205,50],
		"olivedrab4"           =>[105,139,34],
		"ivory1"               =>[255,255,240],
		"ivory2"               =>[238,238,224],
		"ivory3"               =>[205,205,193],
		"ivory4"               =>[139,139,131],
		"beige"                =>[245,245,220],
		"lightyellow1"         =>[255,255,224],
		"lightyellow2"         =>[238,238,209],
		"lightyellow3"         =>[205,205,180],
		"lightyellow4"         =>[139,139,122],
		"lightgoldenrodyellow" =>[250,250,210],
		"yellow1"              =>[255,255,0],
		"yellow2"              =>[238,238,0],
		"yellow3"              =>[205,205,0],
		"yellow4"              =>[139,139,0],
		"warmgrey"             =>[128,128,105],
		"olive"                =>[128,128,0],
		"darkkhaki"            =>[189,183,107],
		"khaki1"               =>[255,246,143],
		"khaki2"               =>[238,230,133],
		"khaki3"               =>[205,198,115],
		"khaki4"               =>[139,134,78],
		"khaki"                =>[240,230,140],
		"palegoldenrod"        =>[238,232,170],
		"lemonchiffon1"        =>[255,250,205],
		"lemonchiffon2"        =>[238,233,191],
		"lemonchiffon3"        =>[205,201,165],
		"lemonchiffon4"        =>[139,137,112],
		"lightgoldenrod1"      =>[255,236,139],
		"lightgoldenrod2"      =>[238,220,130],
		"lightgoldenrod3"      =>[205,190,112],
		"lightgoldenrod4"      =>[139,129,76],
		"banana"               =>[227,207,87],
		"gold1"                =>[255,215,0],
		"gold2"                =>[238,201,0],
		"gold3"                =>[205,173,0],
		"gold4"                =>[139,117,0],
		"cornsilk1"            =>[255,248,220],
		"cornsilk2"            =>[238,232,205],
		"cornsilk3"            =>[205,200,177],
		"cornsilk4"            =>[139,136,120],
		"goldenrod"            =>[218,165,32],
		"goldenrod1"           =>[255,193,37],
		"goldenrod2"           =>[238,180,34],
		"goldenrod3"           =>[205,155,29],
		"goldenrod4"           =>[139,105,20],
		"darkgoldenrod"        =>[184,134,11],
		"darkgoldenrod1"       =>[255,185,15],
		"darkgoldenrod2"       =>[238,173,14],
		"darkgoldenrod3"       =>[205,149,12],
		"darkgoldenrod4"       =>[139,101,8],
		"orange1"              =>[255,165,0],
		"orange2"              =>[238,154,0],
		"orange3"              =>[205,133,0],
		"orange4"              =>[139,90,0],
		"floralwhite"          =>[255,250,240],
		"oldlace"              =>[253,245,230],
		"wheat"                =>[245,222,179],
		"wheat1"               =>[255,231,186],
		"wheat2"               =>[238,216,174],
		"wheat3"               =>[205,186,150],
		"wheat4"               =>[139,126,102],
		"moccasin"             =>[255,228,181],
		"papayawhip"           =>[255,239,213],
		"blanchedalmond"       =>[255,235,205],
		"navajowhite1"         =>[255,222,173],
		"navajowhite2"         =>[238,207,161],
		"navajowhite3"         =>[205,179,139],
		"navajowhite4"         =>[139,121,94],
		"eggshell"             =>[252,230,201],
		"tan"                  =>[210,180,140],
		"brick"                =>[156,102,31],
		"cadmiumyellow"        =>[255,153,18],
		"antiquewhite"         =>[250,235,215],
		"antiquewhite1"        =>[255,239,219],
		"antiquewhite2"        =>[238,223,204],
		"antiquewhite3"        =>[205,192,176],
		"antiquewhite4"        =>[139,131,120],
		"burlywood"            =>[222,184,135],
		"burlywood1"           =>[255,211,155],
		"burlywood2"           =>[238,197,145],
		"burlywood3"           =>[205,170,125],
		"burlywood4"           =>[139,115,85],
		"bisque1"              =>[255,228,196],
		"bisque2"              =>[238,213,183],
		"bisque3"              =>[205,183,158],
		"bisque4"              =>[139,125,107],
		"melon"                =>[227,168,105],
		"carrot"               =>[237,145,33],
		"darkorange"           =>[255,140,0],
		"darkorange1"          =>[255,127,0],
		"darkorange2"          =>[238,118,0],
		"darkorange3"          =>[205,102,0],
		"darkorange4"          =>[139,69,0],
		"orange"               =>[255,128,0],
		"tan1"                 =>[255,165,79],
		"tan2"                 =>[238,154,73],
		"tan3"                 =>[205,133,63],
		"tan4"                 =>[139,90,43],
		"linen"                =>[250,240,230],
		"peachpuff1"           =>[255,218,185],
		"peachpuff2"           =>[238,203,173],
		"peachpuff3"           =>[205,175,149],
		"peachpuff4"           =>[139,119,101],
		"seashell1"            =>[255,245,238],
		"seashell2"            =>[238,229,222],
		"seashell3"            =>[205,197,191],
		"seashell4"            =>[139,134,130],
		"sandybrown"           =>[244,164,96],
		"rawsienna"            =>[199,97,20],
		"chocolate"            =>[210,105,30],
		"chocolate1"           =>[255,127,36],
		"chocolate2"           =>[238,118,33],
		"chocolate3"           =>[205,102,29],
		"chocolate4"           =>[139,69,19],
		"ivoryblack"           =>[41,36,33],
		"flesh"                =>[255,125,64],
		"cadmiumorange"        =>[255,97,3],
		"burntsienna"          =>[138,54,15],
		"sienna"               =>[160,82,45],
		"sienna1"              =>[255,130,71],
		"sienna2"              =>[238,121,66],
		"sienna3"              =>[205,104,57],
		"sienna4"              =>[139,71,38],
		"lightsalmon1"         =>[255,160,122],
		"lightsalmon2"         =>[238,149,114],
		"lightsalmon3"         =>[205,129,98],
		"lightsalmon4"         =>[139,87,66],
		"coral"                =>[255,127,80],
		"orangered1"           =>[255,69,0],
		"orangered2"           =>[238,64,0],
		"orangered3"           =>[205,55,0],
		"orangered4"           =>[139,37,0],
		"sepia"                =>[94,38,18],
		"darksalmon"           =>[233,150,122],
		"salmon1"              =>[255,140,105],
		"salmon2"              =>[238,130,98],
		"salmon3"              =>[205,112,84],
		"salmon4"              =>[139,76,57],
		"coral1"               =>[255,114,86],
		"coral2"               =>[238,106,80],
		"coral3"               =>[205,91,69],
		"coral4"               =>[139,62,47],
		"burntumber"           =>[138,51,36],
		"tomato1"              =>[255,99,71],
		"tomato2"              =>[238,92,66],
		"tomato3"              =>[205,79,57],
		"tomato4"              =>[139,54,38],
		"salmon"               =>[250,128,114],
		"mistyrose1"           =>[255,228,225],
		"mistyrose2"           =>[238,213,210],
		"mistyrose3"           =>[205,183,181],
		"mistyrose4"           =>[139,125,123],
		"snow1"                =>[255,250,250],
		"snow2"                =>[238,233,233],
		"snow3"                =>[205,201,201],
		"snow4"                =>[139,137,137],
		"rosybrown"            =>[188,143,143],
		"rosybrown1"           =>[255,193,193],
		"rosybrown2"           =>[238,180,180],
		"rosybrown3"           =>[205,155,155],
		"rosybrown4"           =>[139,105,105],
		"lightcoral"           =>[240,128,128],
		"indianred"            =>[205,92,92],
		"indianred1"           =>[255,106,106],
		"indianred2"           =>[238,99,99],
		"indianred4"           =>[139,58,58],
		"indianred3"           =>[205,85,85],
		"brown"                =>[165,42,42],
		"brown1"               =>[255,64,64],
		"brown2"               =>[238,59,59],
		"brown3"               =>[205,51,51],
		"brown4"               =>[139,35,35],
		"firebrick"            =>[178,34,34],
		"firebrick1"           =>[255,48,48],
		"firebrick2"           =>[238,44,44],
		"firebrick3"           =>[205,38,38],
		"firebrick4"           =>[139,26,26],
		"red"                  =>[255,0,0],
		"red2"                 =>[238,0,0],
		"red3"                 =>[205,0,0],
		"red4"                 =>[139,0,0],
		"maroon"               =>[128,0,0],
		"sgibeet"              =>[142,56,142],
		"sgislateblue"         =>[113,113,198],
		"sgilightblue"         =>[125,158,192],
		"sgiteal"              =>[56,142,142],
		"sgichartreuse"        =>[113,198,113],
		"sgiolivedrab"         =>[142,142,56],
		"sgibrightgray"        =>[197,193,170],
		"sgisalmon"            =>[198,113,113],
		"sgidarkgray"          =>[85,85,85],
		"sgigray12"            =>[30,30,30],
		"sgigray16"            =>[40,40,40],
		"sgigray32"            =>[81,81,81],
		"sgigray36"            =>[91,91,91],
		"sgigray52"            =>[132,132,132],
		"sgigray56"            =>[142,142,142],
		"sgilightgray"         =>[170,170,170],
		"sgigray72"            =>[183,183,183],
		"sgigray76"            =>[193,193,193],
		"sgigray92"            =>[234,234,234],
		"sgigray96"            =>[244,244,244],
		"white"                =>[255,255,255],
		"gainsboro"            =>[220,220,220],
		"lightgrey"            =>[211,211,211],
		"silver"               =>[192,192,192],
		"darkgray"             =>[169,169,169],
		"gray"                 =>[128,128,128],
		"black"                =>[0,0,0],
		"gray99"               =>[252,252,252],
		"gray98"               =>[250,250,250],
		"gray97"               =>[247,247,247],
		"whitesmoke"           =>[245,245,245],
		"gray95"               =>[242,242,242],
		"gray94"               =>[240,240,240],
		"gray93"               =>[237,237,237],
		"gray92"               =>[235,235,235],
		"gray91"               =>[232,232,232],
		"gray90"               =>[229,229,229],
		"gray89"               =>[227,227,227],
		"gray88"               =>[224,224,224],
		"gray87"               =>[222,222,222],
		"gray86"               =>[219,219,219],
		"gray85"               =>[217,217,217],
		"gray84"               =>[214,214,214],
		"gray83"               =>[212,212,212],
		"gray82"               =>[209,209,209],
		"gray81"               =>[207,207,207],
		"gray80"               =>[204,204,204],
		"gray79"               =>[201,201,201],
		"gray78"               =>[199,199,199],
		"gray77"               =>[196,196,196],
		"gray76"               =>[194,194,194],
		"gray75"               =>[191,191,191],
		"gray74"               =>[189,189,189],
		"gray73"               =>[186,186,186],
		"gray72"               =>[184,184,184],
		"gray71"               =>[181,181,181],
		"gray70"               =>[179,179,179],
		"gray69"               =>[176,176,176],
		"gray68"               =>[173,173,173],
		"gray67"               =>[171,171,171],
		"gray66"               =>[168,168,168],
		"gray65"               =>[166,166,166],
		"gray64"               =>[163,163,163],
		"gray63"               =>[161,161,161],
		"gray62"               =>[158,158,158],
		"gray61"               =>[156,156,156],
		"gray60"               =>[153,153,153],
		"gray59"               =>[150,150,150],
		"gray58"               =>[148,148,148],
		"gray57"               =>[145,145,145],
		"gray56"               =>[143,143,143],
		"gray55"               =>[140,140,140],
		"gray54"               =>[138,138,138],
		"gray53"               =>[135,135,135],
		"gray52"               =>[133,133,133],
		"gray51"               =>[130,130,130],
		"gray50"               =>[127,127,127],
		"gray49"               =>[125,125,125],
		"gray48"               =>[122,122,122],
		"gray47"               =>[120,120,120],
		"gray46"               =>[117,117,117],
		"gray45"               =>[115,115,115],
		"gray44"               =>[112,112,112],
		"gray43"               =>[110,110,110],
		"gray42"               =>[107,107,107],
		"dimgray"              =>[105,105,105],
		"gray40"               =>[102,102,102],
		"gray39"               =>[99,99,99],
		"gray38"               =>[97,97,97],
		"gray37"               =>[94,94,94],
		"gray36"               =>[92,92,92],
		"gray35"               =>[89,89,89],
		"gray34"               =>[87,87,87],
		"gray33"               =>[84,84,84],
		"gray32"               =>[82,82,82],
		"gray31"               =>[79,79,79],
		"gray30"               =>[77,77,77],
		"gray29"               =>[74,74,74],
		"gray28"               =>[71,71,71],
		"gray27"               =>[69,69,69],
		"gray26"               =>[66,66,66],
		"gray25"               =>[64,64,64],
		"gray24"               =>[61,61,61],
		"gray23"               =>[59,59,59],
		"gray22"               =>[56,56,56],
		"gray21"               =>[54,54,54],
		"gray20"               =>[51,51,51],
		"gray19"               =>[48,48,48],
		"gray18"               =>[46,46,46],
		"gray17"               =>[43,43,43],
		"gray16"               =>[41,41,41],
		"gray15"               =>[38,38,38],
		"gray14"               =>[36,36,36],
		"gray13"               =>[33,33,33],
		"gray12"               =>[31,31,31],
		"gray11"               =>[28,28,28],
		"gray10"               =>[26,26,26],
		"gray9"                =>[23,23,23],
		"gray8"                =>[20,20,20],
		"gray7"                =>[18,18,18],
		"gray6"                =>[15,15,15],
		"gray5"                =>[13,13,13],
		"gray4"                =>[10,10,10],
		"gray3"                =>[8,8,8],
		"gray2"                =>[5,5,5],
		"gray1"                =>[3,3,3]
	];

	/**
	* @var Holds the single array of color used in chaining
	*/
	private $color;

	public function hex($isHex = true)
	{
		$this->isHex = $isHex;

		return $this;
	}

	/**
	* Converts RGB Color to Hexa Decimal Format
	*
	* @return array|string
	*/
	public function toHex()
    {
    	if (is_array($this->color))
    	{
    		return $this->convertToHexa($this->color);
    	}
    	else
    	{
    		return $this->color;
    	}
    }

    /**
     * Display Color Tables
     *
     * Lets you see the available colors in a table format 
     *
     * @return void
     */
    public function show()
    {
		$colors = $this->colors;
		ksort($colors);

		echo "<table style='border-collapse:separate; border-spacing: 5px; margin: auto; font-family: Calibri'>";

		$counter = 1;

		foreach($colors as $key => $value)
		{
			if ($counter == 1)
			    echo "  <tr>";

			if (strstr($key, 'black') || strstr($key, 'gray') || strstr($key, 'grey'))
			    $forecolor = 'color: white;';
			else
			    $forecolor = 'color: black;';
			 
			$rgb = 'rgb(' . $value[0] . ', ' . $value[1] . ', ' .$value[2] . ')';


			echo "      <td style='{$forecolor} border: 1px solid gray; background-color: {$rgb}; text-align: center; width: 135;'>{$key}</td>";    

			if ($counter == 8)
			{
			    echo "  </tr>";
			    $counter = 0;   
			}

			$counter++;
		}
		echo "</table>";
		die();
    }

    /**
     * Get Color
     *
     * Lets you get color by passing string color name.
     * If passed string is not found, it returns white
     *
     * @param   mixed
     * @return  mixed   string o array
     */
    private function getColor($colorName, $property = TRUE)
    {
    	if (is_string($colorName))
		{
		    if (array_key_exists($colorName, $this->colors))
		    {
				$this->color = $this->colors[$colorName]; 
		    }
		    else
		    {
				$this->color = [255, 255, 255];
		    }
		}
		else 
		{
		    $this->color = $colorName;
		}

		if ($property) {
			if ($this->isHex) {
				return $this->toHex();
			}

			return $this->color;
		}

		return $this;
    }


    /**
     * Convert RGB Array to HexaDecimal Color Values
     *
     * @param   array 	$rgb
     * @return  string  
     */
    private function convertToHexa($rgb)
    {
		$r = dechex($rgb[0]);
		
		if (strlen($r) < 2) 
			$r = '0' . $r;

		$g = dechex($rgb[1]);
		
		if (strlen($g) < 2) 
			$g = '0' . $g;

		$b = dechex($rgb[2]);
		
		if (strlen($b) < 2) 
			$b = '0' . $b;

		return $r . $g . $b;
    }

    /**
    * Get the color array value when getting color as property. i.e.
    * $color = new (Simmfins\Services\Helpers\ColorHelper)->blue;
    * 
    * @return array
    */
    public function __get($name)
    {
    	return $this->getColor($name);
    }

    /**
    * Get the color array value when getting color as method.  Usually used in chaining. i.e.
    * $color = new (Simmfins\Services\Helpers\ColorHelper)->blue()->toHex();
    * 
    * @return string
    */
    public function __call($method, $args)
    {
    	return $this->getColor($method, FALSE);
    }
}
