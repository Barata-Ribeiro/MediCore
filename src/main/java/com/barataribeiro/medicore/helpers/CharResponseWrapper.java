package com.barataribeiro.medicore.helpers;

import jakarta.servlet.ServletOutputStream;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpServletResponseWrapper;

import java.io.CharArrayWriter;
import java.io.IOException;
import java.io.PrintWriter;

public class CharResponseWrapper extends HttpServletResponseWrapper {
    protected CharArrayWriter charWriter;
    protected PrintWriter writer;
    protected boolean getOutputStreamCalled;
    protected boolean getWriterCalled;

    /**
     * Constructs a response adaptor wrapping the given response.
     *
     * @param response The response to be wrapped
     * @throws IllegalArgumentException if the response is null
     */
    public CharResponseWrapper(HttpServletResponse response) {
        super(response);
        this.charWriter = new CharArrayWriter();
    }

    @Override
    public ServletOutputStream getOutputStream() throws IOException {
        if (getWriterCalled) throw new IllegalStateException("getWriter already called");

        getOutputStreamCalled = true;
        return super.getOutputStream();
    }

    @Override
    public PrintWriter getWriter() throws IOException {
        if (writer != null) return writer;

        if (getOutputStreamCalled) throw new IllegalStateException("getOutputStream already called");

        getWriterCalled = true;
        writer = new PrintWriter(charWriter);
        return writer;
    }

    public String toString() {
        String s = null;
        if (writer != null) s = charWriter.toString();
        return s;
    }
}
