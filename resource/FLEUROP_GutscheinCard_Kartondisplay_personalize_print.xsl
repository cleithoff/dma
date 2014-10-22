<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- Page layout information -->
<xsl:template match="/">

<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
<fo:layout-master-set>
<fo:simple-page-master master-name="main" page-height="297mm" page-width="210mm" font-family="sans-serif" margin="0mm">
<fo:region-body margin="0mm" />
</fo:simple-page-master>
</fo:layout-master-set>

<fo:page-sequence master-reference="main">
<fo:flow flow-name="xsl-region-body">
            <fo:block-container page-break-after="always">
            	<fo:block-container absolute-position="absolute" left="0mm" top="0mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkartondisplaytopper" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="105mm" top="0mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkartondisplaytopper" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="0mm" top="90mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkartondisplaytopper" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="105mm" top="90mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkartondisplaytopper" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="0mm" top="180mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkartondisplaytopper" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="105mm" top="180mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkartondisplaytopper" />
            	</fo:block-container>            	
            </fo:block-container>
</fo:flow>

</fo:page-sequence>

</fo:root>

</xsl:template>

<xsl:template match="data/Product_Service_Plugingutscheincardkartondisplaytopper">

<fo:block font-size="7pt">
<fo:block-container
block-progression-dimension="78mm"
inline-progression-dimension="78mm"
background-color="none"
absolute-position="absolute"
left="12mm"
top="12mm">
            <fo:block>
            	<fo:instream-foreign-object>
              <svg xmlns="http://www.w3.org/2000/svg" width="78mm" height="78mm">
       				<circle cx="39mm" cy="39mm" r="38mm" 
       				stroke="black" 
       				stroke-width="1" 
       				stroke-linecap="butt"
       				stroke-linejoin="miter"
       				stroke-opacity="1"
       				stroke-miterlimit="4"
       				stroke-dasharray="4,6"
       				stroke-dashoffset="0"
       				fill="none"
       				/>
              </svg>
    </fo:instream-foreign-object>
            </fo:block>
</fo:block-container>

<fo:block-container
block-progression-dimension="78mm"
inline-progression-dimension="78mm"
background-color="none"
absolute-position="absolute"
left="12mm"
top="12mm">
<fo:block>
    <fo:external-graphic content-width="68mm" content-height="68mm" padding="5mm">
    	<xsl:attribute name="src">
             <xsl:value-of select="./logo"/>
        </xsl:attribute> 
    </fo:external-graphic>
            </fo:block>
</fo:block-container>    
</fo:block>

</xsl:template>
</xsl:stylesheet>