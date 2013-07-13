<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- Page layout information -->
<xsl:template match="/">
<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">

<fo:layout-master-set>
<fo:simple-page-master master-name="main" page-height="7.9cm" page-width="13.08cm" font-family="sans-serif" margin="1.5cm">
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
<xsl:apply-templates select="addressprint/addresslines" />
</fo:block>
          </fo:footnote-body>
        </fo:footnote>
      </fo:block>
</fo:flow>

</fo:page-sequence>

</fo:root>

</xsl:template>

<xsl:template match="addressprint/addresslines">

<fo:block font-size="7pt" width="65mm" height="32mm">
 <xsl:for-each select="addressline">
   <xsl:if test=".">
    <xsl:value-of select="."/><xsl:text>&#x2028;</xsl:text> 
   </xsl:if>
  </xsl:for-each>

</fo:block>

</xsl:template>

</xsl:stylesheet>