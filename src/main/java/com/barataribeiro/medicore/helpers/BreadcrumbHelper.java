package com.barataribeiro.medicore.helpers;

import com.barataribeiro.medicore.helpers.BreadcrumbItem.BreadcrumbItem;
import org.jetbrains.annotations.NotNull;

import java.util.ArrayList;
import java.util.List;

public class BreadcrumbHelper {
    public List<BreadcrumbItem> buildBreadcrumbs(String userBaseUrl, String @NotNull ... pathItems) {
        List<BreadcrumbItem> breadcrumbs = new ArrayList<>();
        breadcrumbs.add(new BreadcrumbItem("Dashboard", userBaseUrl));

        StringBuilder pathBuilder = new StringBuilder(userBaseUrl);

        for (int i = 0; i < pathItems.length; i += 2) {
            String path = pathItems[i];
            String title = pathItems[i + 1];
            pathBuilder.append("/").append(path);
            breadcrumbs.add(new BreadcrumbItem(title, pathBuilder.toString()));
        }

        return breadcrumbs;
    }
}
