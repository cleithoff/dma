<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- Page layout information -->
<xsl:template match="/">

<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
<fo:layout-master-set>
<fo:simple-page-master master-name="main" page-height="420mm" page-width="297mm" font-family="sans-serif" margin="0mm">
<fo:region-body margin-left="202.5mm" margin-top="9mm" />
</fo:simple-page-master>
</fo:layout-master-set>

<fo:page-sequence master-reference="main">
<fo:flow flow-name="xsl-region-body">
            <fo:block>
            	<xsl:apply-templates select="data/Product_Service_Plugingutscheincardplakata3" />
            </fo:block>
</fo:flow>

</fo:page-sequence>

</fo:root>

</xsl:template>

<xsl:template match="data/Product_Service_Plugingutscheincardplakata3">

<fo:block font-size="7pt">
<!--    <fo:external-graphic content-width="97mm" content-height="97mm">
    	<xsl:attribute name="src">
             <xsl:value-of select="./logo"/>
        </xsl:attribute>
    </fo:external-graphic> -->
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

</xsl:template>
</xsl:stylesheet>