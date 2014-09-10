<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- Page layout information -->
<xsl:template match="/">
<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">

<fo:layout-master-set>
<fo:simple-page-master master-name="main" page-height="54mm" page-width="85mm" font-family="sans-serif" margin="3.5mm" margin-bottom="1.5cm">
<fo:region-body />
<fo:region-before />
</fo:simple-page-master>
</fo:layout-master-set>

<fo:page-sequence master-reference="main">
<fo:flow flow-name="xsl-region-body">
      <fo:block>
        <fo:footnote>
          <fo:inline/>
          <fo:footnote-body>
            <fo:block>
              <xsl:apply-templates select="data/product_personalize" />
            </fo:block>
          </fo:footnote-body>
        </fo:footnote>
      </fo:block>
</fo:flow>

</fo:page-sequence>

</fo:root>

</xsl:template>

<xsl:template match="data/product_personalize">

<fo:block font-size="7pt" color="white" text-align="center" width="65mm" height="32mm">
   <xsl:if test="./gutscheincard_line_1">
    <xsl:value-of select="./gutscheincard_line_1"/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>
   <xsl:if test="./gutscheincard_line_2">
    <xsl:value-of select="./gutscheincard_line_2"/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>

</fo:block>

</xsl:template>

</xsl:stylesheet>