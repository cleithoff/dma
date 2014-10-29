<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- Page layout information -->
<xsl:template match="/">
<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">

<fo:layout-master-set>
<fo:simple-page-master master-name="main" page-height="5.51cm" page-width="11.02cm" font-family="sans-serif" margin="0.5cm" margin-left="1.4cm" margin-bottom="1.8cm">
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

<fo:block font-size="7pt" width="65mm" height="32mm">
   <xsl:if test="./line1">
    <xsl:value-of select="./line1"/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>
   <xsl:if test="./line2">
    <xsl:value-of select="./line2"/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>
   <xsl:if test="./line3">
    <xsl:value-of select="./line3"/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>
   <xsl:if test="./line4">
    <xsl:value-of select="./line4"/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>
   <xsl:if test="./line5">
    <xsl:value-of select="./line5"/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>
   <xsl:if test="./line6">
    <xsl:value-of select="./line6"/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>
   <xsl:if test="./line7">
    <xsl:value-of select="./line7"/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>

</fo:block>

</xsl:template>

</xsl:stylesheet>