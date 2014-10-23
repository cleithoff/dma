<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- Page layout information -->
<xsl:template match="/">

<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
<fo:layout-master-set>
<fo:simple-page-master master-name="main" page-height="450mm" page-width="320mm" font-family="sans-serif" margin="0mm">
<fo:region-body margin-left="0mm" margin-top="0mm" />
</fo:simple-page-master>
</fo:layout-master-set>

<fo:page-sequence master-reference="main">
<fo:flow flow-name="xsl-region-body">
            <fo:block-container page-break-after="always">
            	<fo:block-container absolute-position="absolute" left="0mm" top="0mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkassenwobbler" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="140mm" top="0mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkassenwobbler" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="0mm" top="140mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkassenwobbler" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="140mm" top="140mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkassenwobbler" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="0mm" top="280mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkassenwobbler" />
            	</fo:block-container>
            	<fo:block-container absolute-position="absolute" left="140mm" top="280mm">
            		<xsl:apply-templates select="data/Product_Service_Plugingutscheincardkassenwobbler" />
            	</fo:block-container>
			</fo:block-container>
</fo:flow>

</fo:page-sequence>

</fo:root>

</xsl:template>

<xsl:template match="data/Product_Service_Plugingutscheincardkassenwobbler">

<fo:block font-size="7pt">

<!-- <fo:block-container
block-progression-dimension="119mm"
inline-progression-dimension="119mm"
background-color="none"
absolute-position="absolute"
left="30mm"
top="25.2mm">
            <fo:block>
            	<fo:instream-foreign-object>
              <svg xmlns="http://www.w3.org/2000/svg" width="119mm" height="119mm">
       				<rect width="119mm" height="119mm"
       				stroke="black" 
       				stroke-width="0.625" 
       				stroke-linecap="butt"
       				stroke-linejoin="miter"
       				stroke-opacity="1"
       				stroke-miterlimit="4"
       				stroke-dasharray="0.625,0.625"
       				stroke-dashoffset="0"
       				fill="none"
       				/>
              </svg>
    </fo:instream-foreign-object>
            </fo:block>
</fo:block-container> -->
            
<fo:block-container
block-progression-dimension="119mm"
inline-progression-dimension="119mm"
background-color="none"
absolute-position="absolute"
left="30mm"
top="24mm">
<fo:block margin-left="80.1mm" margin-top="35.1mm">
    <fo:external-graphic content-width="scale-to-fit" scaling="uniform">
    	<xsl:attribute name="padding-left">
             <xsl:value-of select="./paddingleft"/>
        </xsl:attribute>
        <xsl:attribute name="padding-top">
             <xsl:value-of select="./paddingtop"/>
        </xsl:attribute>
        <xsl:attribute name="width">
             <xsl:value-of select="./width"/>
        </xsl:attribute>
        <xsl:attribute name="height">
             <xsl:value-of select="./height"/>
        </xsl:attribute>
    	<xsl:attribute name="src">
             <xsl:value-of select="./logo"/>
        </xsl:attribute>
    </fo:external-graphic>
    </fo:block>
    </fo:block-container>
</fo:block>

</xsl:template>
</xsl:stylesheet>