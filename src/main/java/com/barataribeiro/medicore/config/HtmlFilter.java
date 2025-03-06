package com.barataribeiro.medicore.config;

import com.barataribeiro.medicore.helpers.CharResponseWrapper;
import com.googlecode.htmlcompressor.compressor.HtmlCompressor;
import com.googlecode.htmlcompressor.compressor.YuiCssCompressor;
import com.googlecode.htmlcompressor.compressor.YuiJavaScriptCompressor;
import jakarta.servlet.*;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import org.springframework.stereotype.Component;

import java.io.IOException;

@Component
public class HtmlFilter implements Filter {
    protected FilterConfig config;

    @Override
    public void init(FilterConfig filterConfig) throws ServletException {
        Filter.super.init(filterConfig);
        this.config = filterConfig;
    }

    @Override
    public void doFilter(ServletRequest request, ServletResponse response,
                         FilterChain chain) throws IOException, ServletException {
        ServletResponse newResponse = response;
        if (request instanceof HttpServletRequest) {
            newResponse = new CharResponseWrapper((HttpServletResponse) response);
        }

        chain.doFilter(request, newResponse);

        if (newResponse instanceof CharResponseWrapper) {
            String text = newResponse.toString();

            if (text != null) {
                HtmlCompressor compressor = new HtmlCompressor();
                compressor.setCompressCss(true);
                compressor.setCompressJavaScript(true);
                compressor.setRemoveComments(true);
                compressor.setRemoveIntertagSpaces(true);
                compressor.setRemoveSurroundingSpaces("all");

                compressor.setJavaScriptCompressor(new YuiJavaScriptCompressor());
                compressor.setCssCompressor(new YuiCssCompressor());

                response.getWriter().write(compressor.compress(text));
            }
        }

    }
}
