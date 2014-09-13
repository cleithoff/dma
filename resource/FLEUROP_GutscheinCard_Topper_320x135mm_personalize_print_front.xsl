<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- Page layout information -->
<xsl:template match="/">

<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
<fo:layout-master-set>
<fo:simple-page-master master-name="main" page-height="141mm" page-width="326mm" font-family="sans-serif" margin="3mm">
<fo:region-body margin-left="222.5mm" margin-top="-5mm" />
</fo:simple-page-master>
</fo:layout-master-set>

<fo:page-sequence master-reference="main">
<fo:flow flow-name="xsl-region-body">
            <fo:block>
            	<xsl:apply-templates select="data/Product_Service_Plugingutscheincardplexyglasdisplaytopper" />
            </fo:block>
</fo:flow>

</fo:page-sequence>

</fo:root>

</xsl:template>

<xsl:template match="data/Product_Service_Plugingutscheincardplexyglasdisplaytopper">

<fo:block font-size="7pt">
    <fo:external-graphic content-width="85mm" content-height="85mm">
    	<xsl:attribute name="src">
             <xsl:value-of select="./logo"/>
        </xsl:attribute>
    </fo:external-graphic>
</fo:block>

</xsl:template>
</xsl:stylesheet>