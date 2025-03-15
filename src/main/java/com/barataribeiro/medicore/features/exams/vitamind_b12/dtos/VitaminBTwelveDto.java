package com.barataribeiro.medicore.features.exams.vitamind_b12.dtos;

import com.barataribeiro.medicore.features.exams.vitamind_b12.VitaminBTwelve;
import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.io.Serializable;
import java.util.Date;

/**
 * DTO for {@link VitaminBTwelve}
 */
@Data
@NoArgsConstructor
@AllArgsConstructor
@JsonIgnoreProperties(ignoreUnknown = true)
public class VitaminBTwelveDto implements Serializable {
    private Long id;
    private Double vitaminBTwelveLevel;
    private Date reportDate;
}